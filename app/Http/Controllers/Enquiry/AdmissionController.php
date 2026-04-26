<?php

namespace App\Http\Controllers\Enquiry;

use App\Http\Controllers\Controller;
use App\Models\Enquiry;
use App\Models\Admission;
use App\Models\FeePayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\Mail\AdmissionUpdatedMail;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Traits\SendsAdmissionEmails;

class AdmissionController extends Controller
{
    use SendsAdmissionEmails;
    /**
     * Show Confirmed Admissions list
     */
    public function index(Request $request)
    {
        $search = $request->search;
        $classFilter = $request->class_filter;
        $feeStatus = $request->fee_status;

        $applyClassFilter = function ($query) use ($classFilter) {
            if ($classFilter) {
                preg_match('/\d+/', $classFilter, $matches);
                $classNum = $matches[0] ?? $classFilter;

                $classVariants = [
                    $classFilter,
                    $classNum,
                    $classNum . 'th',
                    $classNum . 'th class',
                    $classNum . 'th Class',
                    'Class ' . $classNum,
                    'Class ' . $classNum . 'th',
                    'class ' . $classNum,
                    'class ' . $classNum . 'th',
                ];
                $query->whereIn('class', array_unique($classVariants));
            }
        };

        // Base query for stats (on full filtered dataset)
        $statsQuery = Admission::query()
            ->when($search, function ($query) use ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('student_name', 'like', "%{$search}%")
                      ->orWhere('roll_number', 'like', "%{$search}%")
                      ->orWhere('class', 'like', "%{$search}%");
                });
            })
            ->when($classFilter, $applyClassFilter)
            ->when($feeStatus, function ($query) use ($feeStatus) {
                if ($feeStatus == 'paid') {
                    $query->where(function($q) {
                        $q->whereIn('payment_mode', ['cash', 'online'])
                          ->orWhere('fee_status', 'paid');
                    });
                } elseif ($feeStatus == 'pending') {
                    $query->where('fee_status', 'pending');
                } elseif ($feeStatus == 'installment') {
                    $query->where('fee_status', 'installment');
                } elseif ($feeStatus == 'pending_installment') {
                    $query->where('fee_status', 'pending installment');
                }
            });

        $totalAdmissionsCount = $statsQuery->count();
        $paidCount = (clone $statsQuery)->where(function($q) {
            $q->whereIn('payment_mode', ['cash', 'online'])
              ->orWhere('fee_status', 'paid');
        })->count();
        $installmentCount = (clone $statsQuery)->where(function($q) {
            $q->where('payment_mode', 'installment')
              ->where('fee_status', '!=', 'pending installment');
        })->count();
        $pendingInstallmentCount = (clone $statsQuery)->where('fee_status', 'pending installment')->count();

        $admissions = (clone $statsQuery)
            ->latest()
            ->paginate(10);

        // Also get confirmed enquiries (paginated)
        $confirmedEnquiries = Enquiry::where('status', 'confirmed')
            ->when($search, function ($query) use ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                      ->orWhere('surname', 'like', "%{$search}%")
                      ->orWhere('class', 'like', "%{$search}%");
                });
            })
            ->when($classFilter, $applyClassFilter)
            ->latest()
            ->paginate(10, ['*'], 'enquiries_page');

        // Predefined classes logic
        $classes = ['5th', '6th', '7th', '8th', '9th', '10th', '11th', '12th'];
        
        // Get classes with student counts for the view (we'll just use the old grouping, it's commented out in view anyway)
        $classesWithCounts = Admission::select('class', DB::raw('count(*) as student_count'))
            ->whereNotNull('class')
            ->where('class', '!=', '')
            ->groupBy('class')
            ->orderBy('class')
            ->get();

        return view('enquiry.admissions.index', compact(
            'admissions', 
            'confirmedEnquiries', 
            'classes', 
            'classesWithCounts',
            'totalAdmissionsCount',
            'paidCount',
            'installmentCount',
            'pendingInstallmentCount'
        ));
    }

    /**
     * Download admission PDF
     */
    public function downloadPdf($id)
    {
        $admission = Admission::findOrFail($id);
        
        // Decode remarks if it's stored as JSON
        if (is_string($admission->remarks)) {
            $admission->remarks = json_decode($admission->remarks, true);
        }
        
        $pdf = Pdf::loadView('enquiry.admissions.pdf', compact('admission'));
        
        return $pdf->download('admission_' . $admission->roll_number . '.pdf');
    }

    /**
     * Get students for a specific class (AJAX endpoint)
     */
    public function getClassStudents($className)
    {
        // Extract number from class name (e.g., "Class 7" -> "7")
        preg_match('/\d+/', $className, $matches);
        $classNum = $matches[0] ?? $className;

        // Handle different class name formats (e.g., "6" and "6th class" should be treated the same)
        $classVariants = [
            $className,
            $classNum,
            $classNum . 'th',
            $classNum . 'th class',
            $classNum . 'th Class',
            'Class ' . $classNum,
            'Class ' . $classNum . 'th'
        ];
        
        $students = Admission::whereIn('class', array_unique($classVariants))
            ->orderBy('roll_number')
            ->select('id', 'student_name', 'roll_number', 'class')
            ->get();
            
        return response()->json([
            'students' => $students
        ]);
    }

    /**
     * Save class attendance
     */
    public function saveAttendance(Request $request)
    {
        $request->validate([
            'class_name' => 'required|string',
            'attendance' => 'required|array',
            'attendance.*.student_id' => 'required|integer',
            'attendance.*.attendance' => 'required|in:present,absent'
        ]);

        $date = now()->toDateString();
        $className = $request->class_name;

        foreach ($request->attendance as $att) {
            \App\Models\ClassAttendance::updateOrCreate(
                [
                    'student_id' => $att['student_id'],
                    'attendance_date' => $date,
                ],
                [
                    'class_name' => $className,
                    'attendance_status' => $att['attendance'],
                ]
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Attendance saved successfully.'
        ]);
    }

    /**
     * Show Add Admission form
     */
    public function create()
    {
        return view('enquiry.admissions.create');
    }

    /**
     * Store new Admission (as Enquiry)
     */
    public function store(Request $request)
    {
        // STEP 1: VALIDATION
        $validated = $request->validate([
            // Required fields
            'first_name' => 'required|string|max:255',
            'middle_name' => 'required|string|max:255',
            'whatsapp' => 'required|regex:/^[6-9][0-9]{9}$/|not_in:0000000000',
            'dob' => 'required|date|before_or_equal:' . now()->subYears(5)->format('Y-m-d'),
            'gender' => 'required|in:male,female',
            'father_mobile' => 'required|regex:/^[6-9][0-9]{9}$/|not_in:0000000000',
            'address' => 'required|string|max:1000',
            'total_fees' => 'required|numeric|min:0',
            'final_fees' => 'nullable|numeric|min:0',
            'parent_declaration' => 'accepted',
            'student_declaration' => 'accepted',
            
            // Optional fields
            'nationality' => 'nullable|string|max:255',
            'father_name' => 'nullable|string|max:255',
            'father_occupation' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'mother_occupation' => 'nullable|string|max:255',
            'student_mobile' => 'nullable|regex:/^[6-9][0-9]{9}$/|not_in:0000000000',
            'mother_mobile' => 'nullable|regex:/^[6-9][0-9]{9}$/|not_in:0000000000',
            'email' => 'nullable|email|max:255',
            'district' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'pincode' => 'nullable|string|max:10',
            'school_name' => 'nullable|string|max:500',
            'previous_marks' => 'nullable|string|max:255',
            'last_year_percentage' => 'nullable|string|max:50',
            'grade' => 'nullable|string|max:50',
            'cgpa' => 'nullable|string|max:50',
            'class_selected' => 'nullable|array',
            'medium' => 'nullable|array',
            'foundation' => 'nullable|array',
            'course' => 'nullable|array',
            'category' => 'nullable|string|max:50',
            'discount_fees' => 'nullable|numeric|min:0',
            'student_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:1024',
            'branch_code' => 'nullable|string|max:50',
            'admission_no' => 'nullable|string|max:50',
            'date' => 'nullable|date',
        ], [
            'dob.before_or_equal' => 'Student must be at least 5 years old to take admission.'
        ]);

        try {
            // STEP 2: GENERATE ENQUIRY NUMBER
            $enquiryNo = str_pad(Enquiry::count() + 1, 4, '0', STR_PAD_LEFT);
            
            // STEP 3: FIELD MAPPING FOR ENQUIRY TABLE
            
            // Convert checkbox arrays to comma-separated strings
            $classStr = is_array($request->class_selected) ? implode(',', $request->class_selected) : '';
            $mediumStr = is_array($request->medium) ? implode(',', $request->medium) : '';
            $foundationStr = is_array($request->foundation) ? implode(',', $request->foundation) : '';
            $courseStr = is_array($request->course) ? implode(',', $request->course) : '';
            
            // Handle file upload for student photo
            $photoPath = null;
            if ($request->hasFile('student_photo')) {
                $photo = $request->file('student_photo');
                $photoPath = \App\Services\ImageOptimizer::optimize($photo, 'profile_images');
            }
            
            // Create comprehensive remarks data from admission form
            $admissionFormData = [
                'form_type' => 'admission_form',
                'nationality' => $validated['nationality'] ?? 'Indian',
                'father_name' => $validated['father_name'] ?? '',
                'father_occupation' => $validated['father_occupation'] ?? '',
                'mother_name' => $validated['mother_name'] ?? '',
                'mother_occupation' => $validated['mother_occupation'] ?? '',
                'student_mobile' => $validated['student_mobile'] ?? '',
                'mother_mobile' => $validated['mother_mobile'] ?? '',
                'district' => $validated['district'] ?? '',
                'state' => $validated['state'] ?? '',
                'pincode' => $validated['pincode'] ?? '',
                'previous_marks' => $validated['previous_marks'] ?? '',
                'last_year_percentage' => $validated['last_year_percentage'] ?? '',
                'grade' => $validated['grade'] ?? '',
                'cgpa' => $validated['cgpa'] ?? '',
                'category' => $validated['category'] ?? '',
                'branch_code' => $validated['branch_code'] ?? 'BCPL-NDCY',
                'admission_no' => $validated['admission_no'] ?? '',
                'student_photo' => $photoPath,
                'declarations' => [
                    'parent_declaration' => $validated['parent_declaration'],
                    'student_declaration' => $validated['student_declaration'],
                ],
                'fees_breakdown' => [
                    'total_fees' => $validated['total_fees'],
                    'discount_fees' => $validated['discount_fees'] ?? 0,
                    'final_fees' => $validated['final_fees'] ?? ($validated['total_fees'] - ($validated['discount_fees'] ?? 0)),
                ],
                'submission_info' => [
                    'submitted_at' => now()->toDateTimeString(),
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]
            ];
            
            // STEP 4: CREATE ENQUIRY RECORD (NOT ADMISSION)
            $enquiry = Enquiry::create([
                'enquiry_no' => $enquiryNo,
                'branch_code' => $validated['branch_code'] ?? 'BCPL-NDCY',
                'date' => $validated['date'] ?? now()->toDateString(),
                
                // Personal information
                'first_name' => $validated['first_name'],
                'middle_name' => $validated['middle_name'],
                'surname' => $validated['surname'],
                'dob' => $validated['dob'],
                'gender' => $validated['gender'],
                
                // Contact information
                'parent_mobile' => $validated['father_mobile'], // Map father_mobile to parent_mobile
                'student_mobile' => $validated['student_mobile'] ?? '',
                'whatsapp' => $validated['father_mobile'], // Use father_mobile as whatsapp
                'email' => $validated['email'] ?? '',
                'address' => $validated['address'],
                
                // Academic information
                'class' => $classStr,
                'school_name' => $validated['school_name'] ?? '',
                'school_time' => '', // Not in admission form
                'last_year_percentage' => $validated['last_year_percentage'] ?? '',
                
                // Course selections
                'medium' => $mediumStr,
                'foundation' => $foundationStr,
                'course' => $courseStr,
                
                // Additional information
                'father_occupation' => $validated['father_occupation'] ?? '',
                'sibling1' => '', // Not in admission form
                'sibling2' => '', // Not in admission form
                'reference1' => '', // Not in admission form
                'reference2' => '', // Not in admission form
                'source' => 'Admission Form', // Indicate this came from admission form
                'remarks' => 'pending', // Status for admission form enquiries
                'parent_feedback' => '', // Not in admission form
                
                // Fees
                'total_fees' => $validated['total_fees'],
                'discount_fees' => $validated['discount_fees'] ?? 0,
                'final_fees' => $validated['final_fees'] ?? ($validated['total_fees'] - ($validated['discount_fees'] ?? 0)),
                
                // Store admission form data in parent_feedback as JSON
                'parent_feedback' => json_encode($admissionFormData),
                
                // Status - IMPORTANT: Set to 'new' for admission form submissions
                'status' => 'new',
            ]);

            \Log::info('Admission form saved as enquiry with ID: ' . $enquiry->id);

            // WhatsApp Auto-Reply
            app(\App\Services\WhatsAppService::class)->sendMessage('new_enquiry', $enquiry);

            return redirect()->route('enquiry.enquiries.index')
                             ->with('success', 'Admission form submitted successfully! Entry is now available in Enquiries for confirmation.');

        } catch (\Exception $e) {
            \Log::error('Error saving admission form as enquiry:', ['error' => $e->getMessage()]);
            return redirect()->back()
                             ->with('error', 'Error submitting admission form: ' . $e->getMessage())
                             ->withInput();
        }
    }

    /**
     * Generate unique admission number
     */
    private function generateAdmissionNo()
    {
        $year = date('Y');
        $sequence = Admission::whereYear('created_at', $year)->count() + 1;
        return 'ADM' . $year . sprintf('%04d', $sequence);
    }

    /**
     * View single Admission
     */
    public function show($id)
    {
        $admission = Admission::with('enquiry')->findOrFail($id);
        
        // If admission has no enquiry relationship, try to find matching enquiry by contact/email
        if (!$admission->enquiry) {
            $matchingEnquiry = null;
            
            // Try to find by contact first
            if ($admission->contact) {
                $matchingEnquiry = \App\Models\Enquiry::where('parent_mobile', $admission->contact)
                    ->orWhere('whatsapp', $admission->contact)
                    ->first();
            }
            
            // If not found, try by email
            if (!$matchingEnquiry && $admission->email) {
                $matchingEnquiry = \App\Models\Enquiry::where('email', $admission->email)->first();
            }
            
            // If found, update admission with enquiry_id
            if ($matchingEnquiry) {
                $admission->enquiry_id = $matchingEnquiry->id;
                $admission->save();
                $admission->load('enquiry');
            }
        }
        
        return view('enquiry.admissions.show', compact('admission'));
    }

    /**
     * Edit Admission
     */
    public function edit($id)
    {
        $admission = Admission::with('enquiry')->findOrFail($id);
        $hasPayments = \App\Models\FeePayment::where('admission_id', $id)->count() > 0;
        return view('enquiry.admissions.edit', compact('admission', 'hasPayments'));
    }

    /**
     * Update Admission
     */
    public function update(Request $request, $id)
    {
        $admission = Admission::findOrFail($id);
        $hasPayments = \App\Models\FeePayment::where('admission_id', $id)->count() > 0;

        // Dynamic validation
        $validationRules = [
            'student_name' => 'required|string',
            'parent_name'  => 'nullable|string',
            'contact'      => 'nullable|string',
            'email'        => 'nullable|email',
            'class'        => 'required|string',
            'roll_number'  => 'nullable|string',
            'total_fees'   => 'required|numeric|min:0',
            'discount_fees' => 'nullable|numeric|min:0',
            'final_fees'   => 'required|numeric|min:0',
            'payment_mode' => 'required|in:cash,online,installment',
            'one_time_amount' => 'nullable|numeric|min:0',
            'remarks'      => 'nullable|string',
        ];

        // Add installment validation if mode is installment
        if ($request->payment_mode === 'installment') {
            $validationRules = array_merge($validationRules, [
                'number_of_installments' => 'required|integer|min:1',
                'installment_start_date' => 'required|date',
                'installment_duration'   => 'required|string',
                'first_payment_amount'   => 'nullable|numeric|min:0',
            ]);
        }

        $validated = $request->validate($validationRules);

        // STEP 1: CALCULATE & VALIDATE FEES
        $totalFees = floatval($validated['total_fees']);
        $discountFees = floatval($validated['discount_fees'] ?? 0);
        $finalFees = $totalFees - $discountFees;

        if ($finalFees < 0) {
            return redirect()->back()->withInput()->with('error', 'Final fees cannot be negative.');
        }

        // Validate paid amount
        $initialPaid = 0;
        if (!$hasPayments) {
            if ($request->payment_mode !== 'installment') {
                $initialPaid = floatval($request->one_time_amount ?? $finalFees);
            } else {
                $initialPaid = floatval($request->first_payment_amount ?? 0);
            }

            if ($initialPaid > $finalFees + 0.01) {
                return redirect()->back()->withInput()->with('error', 'Paid amount (₹' . number_format($initialPaid, 2) . ') cannot exceed final fees (₹' . number_format($finalFees, 2) . ').');
            }
        }

        // STEP 2: PREPARE UPDATE DATA
        $updateData = [
            'student_name'  => $request->student_name,
            'parent_name'   => $request->parent_name,
            'contact'       => $request->contact,
            'email'         => $request->email,
            'class'         => $request->class,
            'roll_number'   => $request->roll_number,
            'total_fees'    => $totalFees,
            'discount_fees' => $discountFees,
            'final_fees'    => $finalFees,
            'total_fee'     => $finalFees, // Maintenance of legacy column
            'payment_mode'  => $request->payment_mode,
            'remarks'       => $request->remarks,
        ];

        // Always update configuration
        if ($request->payment_mode !== 'installment') {
            $updateData['installment_count'] = 1;
            $updateData['installment_type'] = null;
            $updateData['installment_start_date'] = null;
            $updateData['installment_amount'] = $finalFees;
        } else {
            $installmentCount = intval($request->number_of_installments ?: 1);
            $updateData['installment_count'] = $installmentCount;
            $updateData['installment_type'] = $request->installment_duration;
            $updateData['installment_start_date'] = $request->installment_start_date;
            $updateData['installment_amount'] = $finalFees / max($installmentCount, 1);
        }

        try {
            // STEP 3: HANDLE PAYMENTS AND SYNC IN TRANSACTION
            DB::transaction(function () use ($admission, $updateData, $request, $finalFees, $hasPayments, $totalFees, $discountFees) {
                // Update Enquiry table to ensure Fee / Show reflects these changes
                if ($admission->enquiry) {
                    $admission->enquiry->update([
                        'total_fees' => $totalFees,
                        'discount_fees' => $discountFees,
                        'final_fees' => $finalFees
                    ]);
                }

                $admission->update($updateData);

                // Determine if we should record a payment
                $payNowAmount = 0;
                $paymentRemarks = '';

                if (!$hasPayments) {
                    // Recording the VERY FIRST payment
                    if ($request->payment_mode !== 'installment') {
                        $payNowAmount = floatval($request->one_time_amount ?? $finalFees);
                        $paymentRemarks = ($payNowAmount >= $finalFees) ? 'Full Payment (Initial Configuration)' : 'Initial Payment';
                    } else {
                        $payNowAmount = floatval($request->first_payment_amount ?? 0);
                        $paymentRemarks = 'First Installment (Initial Configuration)';
                    }
                } else {
                    $payNowAmount = 0;
                }

                if ($payNowAmount > 0) {
                    // Security Check: Ensure first payment doesn't exceed installment amount
                    if ($request->payment_mode === 'installment' && !$hasPayments) {
                        $installmentCount = intval($updateData['installment_count'] ?? 1);
                        $maxPay = $finalFees / max($installmentCount, 1);
                        if ($payNowAmount > $maxPay + 0.01) {
                            throw new \Exception("First payment amount ₹" . number_format($payNowAmount, 2) . " cannot exceed installment amount ₹" . number_format($maxPay, 2));
                        }
                    }

                    \App\Models\FeePayment::create([
                        'admission_id' => $admission->id,
                        'amount' => $payNowAmount,
                        'payment_mode' => $request->payment_mode === 'installment' ? 'cash' : $request->payment_mode,
                        'payment_date' => now()->toDateString(),
                        'transaction_id' => $request->payment_mode === 'online' ? 'TXN-' . strtoupper(uniqid()) : null,
                        'remarks' => $paymentRemarks
                    ]);

                    // Update cumulative paid amount
                    $admission->increment('paid_amount', $payNowAmount);
                }

                // Always update pending amount and final status
                $admission->refresh();
                $admission->update([
                    'pending_amount' => max($finalFees - $admission->paid_amount, 0),
                    'fee_status' => ($admission->paid_amount >= $finalFees) ? 'paid' : ($admission->installment_count > 1 ? 'installment' : 'partial')
                ]);
            });

            return redirect()
                ->route('enquiry.admissions.index')
                ->with('success', 'Admission updated successfully!');

        } catch (\Exception $e) {
            \Log::error('Admission update failed: ' . $e->getMessage());
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Update failed: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        Admission::findOrFail($id)->delete();

        return redirect()
            ->route('enquiry.admissions.index')
            ->with('success', 'Admission deleted successfully!');
    }
}