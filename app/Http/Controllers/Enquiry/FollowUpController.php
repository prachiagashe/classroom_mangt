<?php

namespace App\Http\Controllers\Enquiry;

use App\Http\Controllers\Controller;
use App\Models\Enquiry;
use App\Models\Admission;
use App\Models\FollowUp;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Traits\SendsAdmissionEmails;
use Illuminate\Support\Str;

class FollowUpController extends Controller
{
    use SendsAdmissionEmails;
    /**
     * Display follow-ups date wise
     */
 public function index(Request $request)
{
    // Default to today's date or first follow-up date
    $selectedDate = $request->date ?? now()->toDateString();
    
    // If no date selected, try to get the earliest follow-up date
    if (!$request->date) {
        $firstFollowUp = FollowUp::whereDate('followup_date', '>=', now()->toDateString())
            ->orderBy('followup_date', 'asc')
            ->first();
        
        if ($firstFollowUp) {
            $selectedDate = is_string($firstFollowUp->followup_date) 
                ? $firstFollowUp->followup_date 
                : $firstFollowUp->followup_date->toDateString();
        }
    }

    // Get only latest pending follow-up per enquiry for selected date
    $followUps = FollowUp::with('enquiry')
        ->whereDate('followup_date', $selectedDate)
        ->where('status', 'pending')
        ->whereHas('enquiry', function($query) {
            $query->where('status', 'follow-up');
        })
        ->orderBy('followup_time')
        ->get()
        ->groupBy('enquiry_id')
        ->map(function($group) {
            return $group->first(); // Get only the latest follow-up per enquiry
        });

    // Get today's follow-ups count
    $todayFollowUpsCount = FollowUp::with('enquiry')
        ->whereDate('followup_date', now()->toDateString())
        ->where('status', 'pending')
        ->whereHas('enquiry', function($query) {
            $query->where('status', 'follow-up');
        })
        ->count();

    // Get array of all dates that have pending follow-ups
    $followUpDates = FollowUp::where('status', 'pending')
        ->whereHas('enquiry', function($query) {
            $query->where('status', 'follow-up');
        })
        ->pluck('followup_date')
        ->map(function($date) {
            return \Carbon\Carbon::parse($date)->toDateString();
        })->unique()->values()->toArray();

    return view('enquiry.followups.index', compact('followUps', 'selectedDate', 'todayFollowUpsCount', 'followUpDates'));
}


    /**
     * Store new follow-up
     */
    public function store(Request $request)
    {
        $request->validate([
            'enquiry_id'    => 'required|exists:enquiries,id',
            'followup_date' => 'required|date|after_or_equal:today|before_or_equal:2100-12-31',
            'followup_time' => 'required',
            'type'          => 'required'
        ], [
            'followup_date.after_or_equal' => 'Follow-up date must be today or a future date',
            'followup_date.before_or_equal' => 'Follow-up date cannot be beyond year 2100',
            'followup_date.date' => 'Please enter a valid date',
        ]);

        $enquiry = Enquiry::findOrFail($request->enquiry_id);

        FollowUp::create([
            'enquiry_id'    => $enquiry->id,
            'student_name'  => $enquiry->first_name . ' ' . $enquiry->surname,
            'followup_date' => $request->followup_date,
            'followup_time' => $request->followup_time,
            'type'          => $request->type,
            // 'followup_type' => $request->type,
            'notes'         => $request->notes,
            // 'status'        => 'pending'
        ]);

        // WhatsApp Follow-Up Scheduled
        app(\App\Services\WhatsAppService::class)->sendMessage('follow_up_scheduled', $followUp);

        // Update enquiry status
        $enquiry->update([
            'status' => 'follow-up'
        ]);

        return response()->json([
            'success'  => true,
            'message'  => 'Follow-up scheduled successfully!',
            'redirect' => route('enquiry.followups.index')
        ]);
    }

    /**
     * Confirm Admission (only update enquiry)
     */
  

public function confirmAdmission($id)
{
    $followUp = FollowUp::with('enquiry')->findOrFail($id);
    $enquiry = $followUp->enquiry;

    // Duplicate Check
    $studentName = ucwords(strtolower(trim($enquiry->first_name . ' ' . $enquiry->middle_name . ' ' . $enquiry->surname)));
    $parentName = ucwords(strtolower(trim($enquiry->middle_name . ' ' . $enquiry->surname)));
    $contact = $enquiry->parent_mobile ?? $enquiry->student_mobile ?? '';
    $className = $enquiry->class ?? '';

    $duplicate = \App\Models\Admission::where('student_name', $studentName)
                                      ->where('contact', $contact)
                                      ->where('class', $className)
                                      ->first();
    if ($duplicate) {
         return response()->json([
            'success' => false,
            'message' => 'Student already exists in this class!'
         ]);
    }

    // Generate unique class-based roll number
    $classPrefix = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $className));
    if (empty($classPrefix)) $classPrefix = 'GEN';
    $baseRoll = sprintf("%s-%s", $classPrefix, date('y'));
    
    $lastRecord = \App\Models\Admission::where('class', $className)
                        ->where('roll_number', 'like', "{$baseRoll}-%")
                        ->orderByRaw("CAST(SUBSTRING_INDEX(roll_number, '-', -1) AS UNSIGNED) DESC")
                        ->first();

    $nextSequence = 1;
    if ($lastRecord) {
        $parts = explode('-', $lastRecord->roll_number);
        $nextSeq = intval(end($parts));
        if ($nextSeq > 0) $nextSequence = $nextSeq + 1;
    }

    while (\App\Models\Admission::where('roll_number', sprintf("%s-%04d", $baseRoll, $nextSequence))->exists()) {
        $nextSequence++;
    }
    $rollNumber = sprintf("%s-%04d", $baseRoll, $nextSequence);

    // Store into admissions table - fetching fees from enquiry and other data from follow-up
    $admission = Admission::create([
        'enquiry_id'     => $enquiry->id,
        'student_name'   => $studentName,
        'parent_name'    => $parentName,
        'contact'        => $contact,
        'email'          => $enquiry->email,
        'class'          => $className,
        'roll_number'    => $rollNumber,
        'admission_date' => now(),
        'fee_status'     => 'pending',
        'total_fee'      => $enquiry->final_fees ?? 0, // Fees from enquiry table
        'paid_amount'    => 0,
        'address'        => $enquiry->address,
        'date_of_birth'  => $enquiry->dob,
        'blood_group'    => $enquiry->blood_group ?? '',
        'previous_school'=> $enquiry->school_name,
    ]);

    // Send confirmation email
    $this->sendAdmissionConfirmationEmail($enquiry->first_name . ' ' . $enquiry->surname, $enquiry->email, $enquiry->class);

    // Update enquiry status
    $enquiry->update(['status' => 'confirmed']);

    // WhatsApp Confirmation Message
    app(\App\Services\WhatsAppService::class)->sendMessage('confirmation', $admission);

    // Notify the student about their admission
    if ($enquiry->email) {
        NotificationService::notifyStudentByEmail(
            $enquiry->email,
            'Admission Confirmed',
            "Welcome to Bansal Classes! Your admission for Class {$className} has been confirmed. Roll No: {$rollNumber}.",
            'admission',
            [
                'admission_id' => $admission->id,
                'roll_number' => $rollNumber,
            ]
        );
    }

    return response()->json([
        'success' => true,
        'message' => 'Admission confirmed successfully!',
        'roll_number' => $rollNumber
    ]);
}


    /**
     * Reject enquiry
     */
    public function rejectFollowUp($id)
    {
        $followUp = FollowUp::findOrFail($id);

        $enquiry = Enquiry::findOrFail($followUp->enquiry_id);

        $enquiry->update([
            'status' => 'rejected'
        ]);

        $this->sendAdmissionRejectionEmail(
            $enquiry->first_name . ' ' . $enquiry->surname,
            $enquiry->email,
            $enquiry->class ?? 'Unknown'
        );

        return back()->with('success', 'Enquiry rejected successfully!');
    }

    /**
     * Mark as contacted
     */
    public function markContacted($id)
    {
        $followUp = FollowUp::findOrFail($id);
        
        $followUp->update([
            'status' => 'contacted',
            'remarks' => 'Contacted Successfully'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Marked as contacted successfully!',
            'status' => 'contacted'
        ]);
    }

    /**
     * Reschedule follow-up
     */
    public function reschedule(Request $request, $followup)
    {
        try {
            // Basic validation
            $request->validate([
                'next_followup_date' => 'required|date|after_or_equal:today|before_or_equal:2100-12-31',
                'followup_time' => 'required',
                'notes' => 'nullable'
            ], [
                'next_followup_date.after_or_equal' => 'Follow-up date must be today or a future date',
                'next_followup_date.before_or_equal' => 'Follow-up date cannot be beyond year 2100',
                'next_followup_date.date' => 'Please enter a valid date',
            ]);

            // Find the follow-up to reschedule
            $oldFollowUp = FollowUp::findOrFail($followup);
            
            // Mark old follow-up as rescheduled
            $oldFollowUp->update(['status' => 'rescheduled']);
            
            // Create new follow-up record
            $newFollowUp = FollowUp::create([
                'enquiry_id' => $oldFollowUp->enquiry_id,
                'student_name' => $oldFollowUp->student_name,
                'followup_date' => $request->next_followup_date,
                'followup_time' => $request->followup_time,
                'type' => $oldFollowUp->type,
                'notes' => $request->notes ?? '',
                'status' => 'pending'
            ]);

            // WhatsApp Reschedule Notification
            app(\App\Services\WhatsAppService::class)->sendMessage('reschedule', $newFollowUp);

            return response()->json([
                'success' => true,
                'message' => 'Follow-up rescheduled successfully!',
                'followup_id' => $newFollowUp->id,
                'next_followup_date' => $request->next_followup_date,
                'followup_time' => $request->followup_time
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add note to follow-up
     */
    public function addNote(Request $request, $id)
    {
        $request->validate([
            'notes' => 'required'
        ]);

        $followUp = FollowUp::findOrFail($id);
        
        $existingNotes = $followUp->notes ?? '';
        $newNotes = $existingNotes . "\n\n" . $request->notes;

        $followUp->update([
            'notes' => $newNotes
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Note added successfully!',
            'notes' => $newNotes
        ]);
    }
}
