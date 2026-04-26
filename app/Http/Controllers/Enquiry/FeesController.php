<?php

namespace App\Http\Controllers\Enquiry;

use App\Http\Controllers\Controller;
use App\Models\Admission;
use App\Models\Enquiry;
use App\Models\FeePayment;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\PaymentReceiptMail;

class FeesController extends Controller
{
    public function index(Request $request)
    {
        // 1. Calculate summary statistics directly using SQL aggregations 
        
        // Total Collection = SUM of all paid_amount values from the fee records table
        $totalCollection = \App\Models\FeePayment::sum('amount');
        
        // Total Pending = SUM of (total_fee - paid_amount) across all admissions
        $totalPending = Admission::sum('total_fee') - Admission::sum('paid_amount');
        
        // Paid Students = COUNT where paid_amount is greater than or equal to total_fee
        $paidCount = Admission::whereColumn('paid_amount', '>=', 'total_fee')->count();
        
        // Overdue Students = COUNT where pending_amount > 0 and 30 days old (equivalent to due_date passed)
        $overdueCount = Admission::whereColumn('total_fee', '>', 'paid_amount')
            ->where('fee_status', 'pending')
            ->where('created_at', '<', now()->subDays(30))
            ->count();

        // Base Query: Only Confirmed Students
        $baseQuery = Admission::with(['enquiry', 'feePayments'])
            ->whereHas('enquiry', function($query) {
                // Ensure admission_status / enquiry status is strictly 'confirmed'
                $query->where('status', 'confirmed');
            });
            
        // 2. Apply Custom Fee Status Filter Logic
        $feeStatusFilter = $request->fee_status;
        
        if ($feeStatusFilter === 'paid') {
            $baseQuery->whereColumn('paid_amount', '>=', 'total_fee');
        } elseif ($feeStatusFilter === 'installment') {
            // Partial (Installment): paid_amount > 0 AND pending_amount > 0
            $baseQuery->where('paid_amount', '>', 0)
                      ->whereColumn('total_fee', '>', 'paid_amount');
        } elseif ($feeStatusFilter === 'pending_installment' || $feeStatusFilter === 'pending') {
            // Pending: paid_amount = 0 AND pending_amount > 0
            $baseQuery->where('paid_amount', '<=', 0)
                      ->whereColumn('total_fee', '>', 'paid_amount');
        } elseif ($feeStatusFilter === 'overdue') {
            // Overdue: pending_amount > 0 AND due_date < current_date
            $baseQuery->whereColumn('total_fee', '>', 'paid_amount')
                      ->where('created_at', '<', now()->subDays(30));
        }

        // 3. Paginate the admissions for the table display (10 per page)
        $paginatedAdmissions = $baseQuery
            ->latest()
            ->paginate(10)
            ->appends(request()->query());

        // Transform the paginated collection while preserving the Paginator structure
        $paginatedAdmissions->getCollection()->transform(function ($admission) {
            $enquiry = $admission->enquiry;
            
            if (!$enquiry) {
                return null;
            }
            
            // DYNAMIC CALCULATIONS (Same as before)
            $totalFee = $admission->total_fee;
            $paidAmount = $admission->paid_amount;
            $pendingAmount = $admission->pending_amount ?? ($totalFee - $paidAmount);
            
            $totalPaidFromPayments = $admission->feePayments->sum('amount');
            if ($totalPaidFromPayments > $paidAmount) {
                $paidAmount = $totalPaidFromPayments;
                $pendingAmount = $totalFee - $paidAmount;
            }
            
            $paymentMode = strtolower(trim($admission->payment_mode ?? 'cash'));
            $paymentModeDisplay = match($paymentMode) {
                'cash' => 'Cash',
                'online' => 'Online',
                'installment' => 'Installment',
                '' => 'Not Set',
                default => ucfirst($paymentMode)
            };
            
            if ($pendingAmount <= 0) {
                $feeStatus = 'Complete';
                $statusClass = 'bg-green-100 text-green-600';
            } elseif ($pendingAmount > 0 && $admission->created_at->diffInDays(now()) > 30) {
                $feeStatus = 'Overdue';
                $statusClass = 'bg-red-100 text-red-600';
            } elseif ($paymentMode === 'installment' && $paidAmount > 0 && $pendingAmount > 0) {
                $feeStatus = 'Partial';
                $statusClass = 'bg-blue-100 text-blue-600';
            } else {
                $feeStatus = 'Pending';
                $statusClass = 'bg-yellow-100 text-yellow-600';
            }

            return [
                'id' => $enquiry->id,
                'admission_id' => $admission->id,
                'student_name' => $admission->student_name,
                'payment_mode' => $paymentModeDisplay,
                'contact' => $admission->contact,
                'class' => $admission->class,
                'total_fee' => $totalFee,
                'discount' => $enquiry->discount_fees ?? 0,
                'paid_amount' => $paidAmount,
                'pending_amount' => $pendingAmount,
                'fee_status' => $feeStatus,
                'status_class' => $statusClass,
                'admission_date' => $admission->created_at,
                'enquiry_id' => $enquiry->id,
                'payment_count' => $admission->feePayments->count() ?: 1,
            ];
        });

        // Use the paginated object as $enquiries for blade
        $enquiries = $paginatedAdmissions;

        return view('enquiry.fees.index', compact(
            'enquiries',
            'totalCollection',
            'totalPending',
            'overdueCount',
            'paidCount'
        ));
    }

    /**
     * Show detailed fee information for a specific student
     */
    public function show($id)
    {
        $admission = Admission::with('enquiry')->findOrFail($id);

        $installments = FeePayment::where('admission_id', $admission->id)
            ->orderBy('payment_date', 'desc')
            ->get();

        $totalPaid = $installments->sum('amount');

        $enquiry = $admission->enquiry;
        $totalFees = $enquiry?->total_fees ?? $admission->total_fee ?? 0;
        $discountFees = $enquiry?->discount_fees ?? 0;
        $finalFees = $enquiry?->final_fees ?? ($totalFees - $discountFees);

        $pendingAmount = max($finalFees - $totalPaid, 0);

        // Generate Installment Schedule
        $expectedInstallments = collect();
        $installmentCount = $admission->installment_count ?? 1;
        $installmentAmount = $admission->installment_amount ?? $finalFees;
        $installmentType = strtolower($admission->installment_type ?? 'one-time');
        
        $startDate = $admission->installment_start_date 
            ? \Carbon\Carbon::parse($admission->installment_start_date) 
            : $admission->created_at;

        $remainingPaid = $totalPaid;
        
        // We will process payments chronologically (oldest first) to attach actual dates to the virtual schedule
        $chronologicalPayments = FeePayment::where('admission_id', $admission->id)
            ->orderBy('payment_date', 'asc')
            ->orderBy('id', 'asc')
            ->get();
            
        $paymentIteratorIndex = 0;
        $unallocatedPaymentRemainder = 0;

        for ($i = 1; $i <= $installmentCount; $i++) {
            $dueDate = clone $startDate;
            
            if ($i > 1) {
                switch ($installmentType) {
                    case 'weekly':
                        $dueDate->addWeeks($i - 1);
                        break;
                    case 'monthly':
                        $dueDate->addMonths($i - 1);
                        break;
                    case 'quarterly':
                        $dueDate->addMonths(($i - 1) * 3);
                        break;
                    case 'yearly':
                        $dueDate->addYears($i - 1);
                        break;
                }
            }

            // The last installment takes whatever is left of the final fees to avoid rounding error
            $thisInstallmentAmountDue = ($i === $installmentCount) 
                ? ($finalFees - ($installmentAmount * ($installmentCount - 1))) 
                : $installmentAmount;
            
            // Allocate the remaining paid money to this specific installment
            $paidForThis = min($thisInstallmentAmountDue, max(0, $remainingPaid));
            $remainingPaid -= $paidForThis;
            $pendingForThis = $thisInstallmentAmountDue - $paidForThis;
            
            $status = 'Pending';
            if ($paidForThis >= $thisInstallmentAmountDue) {
                $status = 'Paid Installment';
            } elseif ($paidForThis > 0 && $pendingForThis > 0) {
                $status = 'Partial';
            } elseif ($dueDate->copy()->startOfDay()->isPast() && $pendingForThis > 0) {
                $status = 'Overdue';
            }
            
            $lastPaymentDateForThisInstallment = null;
            $lastTransactionIdForThisInstallment = null;
            
            // Consume chronological payments to figure out WHEN this was paid
            $amountToMapForDates = $paidForThis;
            while ($amountToMapForDates > 0 && $paymentIteratorIndex < $chronologicalPayments->count()) {
                $payment = $chronologicalPayments[$paymentIteratorIndex];
                
                $availableFromPayment = ($unallocatedPaymentRemainder > 0) ? $unallocatedPaymentRemainder : $payment->amount;
                
                if ($availableFromPayment <= $amountToMapForDates) {
                    // This payment is fully consumed by this installment
                    $amountToMapForDates -= $availableFromPayment;
                    $unallocatedPaymentRemainder = 0;
                    $lastPaymentDateForThisInstallment = $payment->payment_date->format('Y-m-d');
                    $lastTransactionIdForThisInstallment = $payment->transaction_id;
                    $paymentIteratorIndex++;
                } else {
                    // This payment covers the rest of this installment, and has leftover for the next one
                    $unallocatedPaymentRemainder = $availableFromPayment - $amountToMapForDates;
                    $amountToMapForDates = 0;
                    $lastPaymentDateForThisInstallment = $payment->payment_date->format('Y-m-d');
                    $lastTransactionIdForThisInstallment = $payment->transaction_id;
                    // Don't increment index, we need the leftover for the next loop
                }
            }
            
            $expectedInstallments->push([
                'installment_number' => $i,
                'due_date' => $dueDate,
                'amount' => $thisInstallmentAmountDue,
                'paid_amount' => $paidForThis,
                'status' => $status,
                'pending_amount' => $pendingForThis,
                'payment_date' => $lastPaymentDateForThisInstallment,
                'transaction_id' => $lastTransactionIdForThisInstallment
            ]);
        }

        return view('enquiry.fees.show', [
            'admission' => $admission,
            'enquiry' => $admission->enquiry,
            'installments' => $installments, // actual payments 
            'expectedInstallments' => $expectedInstallments, // schedule
            'totalPaid' => $totalPaid,
            'pendingAmount' => $pendingAmount,
            'totalFees' => $totalFees,
            'discountFees' => $discountFees,
            'finalFees' => $finalFees,
        ]);
    }

    /**
     * Store new payment for a specific admission.
     */
    public function storePayment(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'payment_mode' => 'required|string',
            'payment_date' => 'required|date',
            'remarks' => 'nullable|string'
        ]);

        $admission = Admission::findOrFail($id);
        
        // Calculate exact pending amount before allowing payment
        $enquiry = $admission->enquiry;
        $totalFees = $enquiry?->final_fees ?? $admission->total_fee ?? 0;
        $totalPaid = $admission->feePayments()->sum('amount');
        $pendingAmount = max($totalFees - $totalPaid, 0);

        if ($pendingAmount <= 0) {
            return redirect()->back()
                ->with('error', 'Fees already completed');
        }

        if ($request->amount > $pendingAmount) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Payment exceeds total fee amount');
        }

        try {
            DB::transaction(function () use ($admission, $request) {
                // 1. Create the new FeePayment record
                // Note: We don't have installment_number column, so we skip it
                FeePayment::create([
                    'admission_id' => $admission->id,
                    'amount' => $request->amount,
                    'payment_mode' => $request->payment_mode,
                    'payment_date' => $request->payment_date,
                    'transaction_id' => $request->payment_mode === 'online' ? 'TXN-' . strtoupper(uniqid()) : null,
                    'remarks' => $request->remarks ?: ucfirst($request->payment_mode) . ' payment'
                ]);

                // 2. Add the payment amount by recalculating from fee_payments directly
                $newPaidAmount = \App\Models\FeePayment::where('admission_id', $admission->id)->sum('amount');
                
                // 3. Update the admission record
                $admission->paid_amount = $newPaidAmount;
                // Calculate status: if paid >= total, mark as complete
                $totalFee = $admission->total_fee;
                $admission->fee_status = ($newPaidAmount >= $totalFee) ? 'complete' : 'installment';
                $admission->save();
            });

            return redirect()->route('enquiry.fees.show', $id)
                ->with('success', 'Payment recorded successfully!');
                
        } catch (\Exception $e) {
            \Log::error('Fee payment recording failed: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to record payment. Please try again.');
        }

        // Notify the student about the payment (outside transaction for safety)
        if ($admission->email) {
            $formattedAmount = number_format($request->amount, 2);
            NotificationService::notifyStudentByEmail(
                $admission->email,
                'Fee Payment Received',
                "Your payment of ₹{$formattedAmount} has been recorded successfully.",
                'fee_payment',
                [
                    'admission_id' => $admission->id,
                    'amount' => $request->amount,
                ]
            );
        }
    }

    /**
     * Update the installment schedule parameters for a specific admission.
     */
    public function updateSchedule(Request $request, $id)
    {
        $request->validate([
            'installment_count' => 'required|integer|min:1|max:24',
            'installment_type' => 'required|in:weekly,monthly,quarterly,yearly',
            'installment_start_date' => 'required|date'
        ]);

        $admission = Admission::findOrFail($id);

        try {
            // Recompute exactly what a single installment amount should be roughly under the new plan
            $enquiry = $admission->enquiry;
            $finalFees = $enquiry?->final_fees ?? $admission->total_fee ?? 0;
            
            $newCount = intval($request->installment_count);
            $newAmount = $finalFees / max(1, $newCount);

            $admission->update([
                'installment_count' => $newCount,
                'installment_type' => $request->installment_type,
                'installment_start_date' => $request->installment_start_date,
                'installment_amount' => $newAmount,
                'payment_mode' => $newCount > 1 ? 'installment' : 'cash'
            ]);

            return redirect()->route('enquiry.fees.show', $id)
                ->with('success', 'Installment schedule updated successfully!');
                
        } catch (\Exception $e) {
            \Log::error('Schedule update failed: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update schedule: ' . $e->getMessage());
        }
    }

    /**
     * Send a payment receipt email to the student/parent.
     */
    public function sendReceipt($paymentId)
    {
        $payment = FeePayment::findOrFail($paymentId);
        $admission = $payment->admission;
        $enquiry = $admission->enquiry;

        if (empty($enquiry->email)) {
            return redirect()->back()->with('error', 'Cannot send receipt: The student does not have a registered email address.');
        }

        try {
            // Determine the "installment number" by chronologically ranking this payment
            $chronologicalPayments = $admission->feePayments()->orderBy('payment_date', 'asc')->orderBy('id', 'asc')->get();
            $installmentNumber = $chronologicalPayments->search(function ($p) use ($paymentId) {
                return $p->id == $paymentId;
            }) + 1;

            // Calculate remaining balance dynamically
            $finalFees = $enquiry?->final_fees ?? $admission->total_fee ?? 0;
            $totalPaidSoFar = $admission->feePayments()->where('id', '<=', $paymentId)->sum('amount');
            $remainingBalance = max($finalFees - $totalPaidSoFar, 0);

            // Send Mail
            Mail::to($enquiry->email)->send(new PaymentReceiptMail(
                $payment, 
                $admission, 
                $enquiry, 
                $installmentNumber, 
                $remainingBalance
            ));

            return redirect()->back()->with('success', 'Payment receipt sent successfully to ' . $enquiry->email);
        } catch (\Exception $e) {
            \Log::error('Failed to send payment receipt: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to send payment receipt. Please verify mail configurations.');
        }
    }

    /**
     * Generate and download a PDF receipt for a specific payment.
     */
    public function downloadPdf($paymentId)
    {
        $payment = FeePayment::findOrFail($paymentId);
        $admission = $payment->admission;
        $enquiry = $admission->enquiry;

        // Determine the "installment number" by chronologically ranking this payment
        $chronologicalPayments = $admission->feePayments()->orderBy('payment_date', 'asc')->orderBy('id', 'asc')->get();
        $installmentNumber = $chronologicalPayments->search(function ($p) use ($paymentId) {
            return $p->id == $paymentId;
        }) + 1;

        // Calculate running balances up to this payment specifically
        $finalFees = $enquiry?->final_fees ?? $admission->total_fee ?? 0;
        $totalPaidSoFar = $admission->feePayments()->where('id', '<=', $paymentId)->sum('amount');
        $remainingBalance = max($finalFees - $totalPaidSoFar, 0);

        // Format receipt number
        $receiptNo = 'BC' . date('Y', strtotime($payment->payment_date)) . '-' . str_pad($payment->id, 4, '0', STR_PAD_LEFT);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('enquiry.fees.receipt_pdf', [
            'payment' => $payment,
            'admission' => $admission,
            'enquiry' => $enquiry,
            'installmentNumber' => $installmentNumber,
            'finalFees' => $finalFees,
            'totalPaidSoFar' => $totalPaidSoFar,
            'remainingBalance' => $remainingBalance,
            'receiptNo' => $receiptNo
        ]);

        $filename = 'Receipt_' . $receiptNo . '_' . preg_replace('/[^a-zA-Z0-9_-]/', '', $enquiry->first_name) . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Send a WhatsApp fee reminder to the student/parent.
     */
    public function whatsappReminder($id)
    {
        $admission = Admission::with('enquiry')->findOrFail($id);
        
        // Calculate pending amount
        $finalFees = $admission->enquiry?->final_fees ?? $admission->total_fee ?? 0;
        $totalPaid = $admission->feePayments()->sum('amount');
        $pendingAmount = max($finalFees - $totalPaid, 0);

        if ($pendingAmount <= 0) {
            return redirect()->back()->with('error', 'Cannot send reminder: Fees are already completed.');
        }

        try {
            // Trigger WhatsApp (Asynchronous)
            app(\App\Services\WhatsAppService::class)->sendMessage('fee_reminder', $admission, [
                'amount' => $pendingAmount
            ]);

            return redirect()->back()->with('success', 'Fee reminder sent to WhatsApp queue successfully!');
        } catch (\Exception $e) {
            \Log::error('Failed to trigger fee reminder: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to send WhatsApp reminder.');
        }
    }
}