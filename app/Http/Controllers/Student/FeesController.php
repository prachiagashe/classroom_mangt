<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Admission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class FeesController extends Controller
{
    /**
     * Display student fees page.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get student's admission record
        $admission = Admission::where('email', $user->email)->first();
        
        if (!$admission) {
            return redirect()->route('student.dashboard')
                ->with('error', 'Admission record not found');
        }
        
        // Calculate fee details
        $totalFee = $admission->total_fee ?? 0;
        $paidAmount = $admission->paid_amount ?? 0;
        $pendingAmount = $totalFee - $paidAmount;
        
        // Calculate installment details
        $installmentType = $admission->installment_type ?? 'full';
        $installmentCount = $admission->installment_count ?? 0;
        $installmentAmount = $admission->installment_amount ?? 0;
        $nextDueDate = $admission->installment_start_date ? Carbon::parse($admission->installment_start_date) : null;
        
        // Generate installment schedule with flexible payment tracking
        $installments = [];
        $remainingPaidAmount = $paidAmount;
        
        if ($installmentType != 'full' && $installmentCount > 0 && $nextDueDate) {
            for ($i = 1; $i <= $installmentCount; $i++) {
                $installmentDate = clone $nextDueDate;
                
                // Calculate date based on installment type
                if ($installmentType == 'monthly') {
                    $installmentDate->addMonths($i - 1);
                } elseif ($installmentType == 'quarterly') {
                    $installmentDate->addMonths(($i - 1) * 3);
                } elseif ($installmentType == 'yearly') {
                    $installmentDate->addYears($i - 1);
                }
                
                $isPast = $installmentDate->isPast();
                
                // Calculate if this installment is paid based on remaining amount
                $isPaid = $remainingPaidAmount >= $installmentAmount;
                $paidAmountForThisInstallment = $isPaid ? $installmentAmount : max(0, $remainingPaidAmount);
                
                // Deduct the amount paid for this installment
                if ($isPaid) {
                    $remainingPaidAmount -= $installmentAmount;
                }
                
                $isOverdue = $isPast && !$isPaid;
                
                // Calculate actual amount paid (could be partial)
                $actualPaidAmount = $paidAmountForThisInstallment;
                $remainingAmountForThisInstallment = $installmentAmount - $actualPaidAmount;
                
                $installments[] = [
                    'number' => $i,
                    'scheduled_amount' => $installmentAmount,
                    'paid_amount' => $actualPaidAmount,
                    'remaining_amount' => $remainingAmountForThisInstallment,
                    'due_date' => $installmentDate->format('M d, Y'),
                    'due_date_raw' => $installmentDate,
                    'status' => $actualPaidAmount >= $installmentAmount ? 'paid' : ($isOverdue ? 'overdue' : 'pending'),
                    'is_past' => $isPast,
                    'is_fully_paid' => $actualPaidAmount >= $installmentAmount,
                    'is_partially_paid' => $actualPaidAmount > 0 && $actualPaidAmount < $installmentAmount,
                    'is_overdue' => $isOverdue,
                    'payment_progress' => $installmentAmount > 0 ? ($actualPaidAmount / $installmentAmount) * 100 : 0
                ];
            }
        }
        
        // Calculate paid installments count (fully paid)
        $paidInstallments = 0;
        $partiallyPaidInstallments = 0;
        
        foreach ($installments as $installment) {
            if ($installment['is_fully_paid']) {
                $paidInstallments++;
            } elseif ($installment['is_partially_paid']) {
                $partiallyPaidInstallments++;
            }
        }
        
        // Check for overdue installments
        $isOverdue = false;
        $overdueDays = 0;
        if ($nextDueDate && $nextDueDate->isPast() && $pendingAmount > 0) {
            $isOverdue = true;
            $overdueDays = $nextDueDate->diffInDays(Carbon::now());
        }
        
        // Check for due within 3 days
        $isDueSoon = false;
        if ($nextDueDate && $nextDueDate->diffInDays(Carbon::now()) <= 3 && $pendingAmount > 0) {
            $isDueSoon = true;
        }
        
        // Calculate payment status
        $paymentStatus = 'pending';
        if ($pendingAmount <= 0) {
            $paymentStatus = 'paid';
        } elseif ($isOverdue) {
            $paymentStatus = 'overdue';
        } elseif ($isDueSoon) {
            $paymentStatus = 'due-soon';
        }
        
        return view('students.fees', compact(
            'admission',
            'totalFee',
            'paidAmount',
            'pendingAmount',
            'installmentType',
            'installmentCount',
            'installmentAmount',
            'nextDueDate',
            'isOverdue',
            'overdueDays',
            'isDueSoon',
            'paymentStatus',
            'installments',
            'paidInstallments',
            'partiallyPaidInstallments'
        ));
    }
    
    /**
     * Get fee statistics for API.
     */
    public function getFeeStats()
    {
        $user = Auth::user();
        $admission = Admission::where('email', $user->email)->first();
        
        if (!$admission) {
            return response()->json(['error' => 'Admission record not found'], 404);
        }
        
        $totalFee = $admission->total_fee ?? 0;
        $paidAmount = $admission->paid_amount ?? 0;
        $pendingAmount = $totalFee - $paidAmount;
        $nextDueDate = $admission->installment_start_date ? Carbon::parse($admission->installment_start_date) : null;
        
        // Calculate alert counts
        $alertCount = 0;
        if ($nextDueDate && $nextDueDate->isPast() && $pendingAmount > 0) {
            $alertCount = 1; // Overdue alert
        }
        
        return response()->json([
            'totalFee' => number_format($totalFee, 2),
            'paidAmount' => number_format($paidAmount, 2),
            'pendingAmount' => number_format($pendingAmount, 2),
            'nextDueDate' => $nextDueDate ? $nextDueDate->format('M d, Y') : null,
            'alertCount' => $alertCount,
            'paymentStatus' => $pendingAmount <= 0 ? 'paid' : 'pending'
        ]);
    }
}
