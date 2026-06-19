<?php

namespace App\Services;

use App\Jobs\SendWhatsAppMessageJob;
use Illuminate\Support\Facades\Log;
use App\Models\Enquiry;
use App\Models\Admission;
use App\Models\FollowUp;

class WhatsAppService
{
    /**
     * Centralized method to send WhatsApp messages based on event type
     * 
     * @param string $type The trigger event type
     * @param mixed $model The underlying model (Enquiry, Admission, or FollowUp)
     * @param array $extra Optional extra data (e.g. fee amount)
     */
    public function sendMessage($type, $model, $extra = [])
    {
        try {
            $data = $this->prepareMessageData($type, $model, $extra);
            
            if (!$data) {
                Log::warning("WhatsApp Service: No message prepared for type '{$type}'");
                return false;
            }

            // Dispatch job for asynchronous processing
            SendWhatsAppMessageJob::dispatch(
                $data['phone'],
                $data['message'],
                $data['enquiry_id'],
                $type
            );

            return true;
        } catch (\Exception $e) {
            Log::error("WhatsApp Service sendMessage error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Prepare message text and phone number based on trigger type
     */
    protected function prepareMessageData($type, $model, $extra)
    {
        $instituteName = env('INSTITUTE_NAME', 'StudyFlow Classes');
        $phone = null;
        $message = null;
        $enquiryId = null;

        // Resolve common data
        if ($model instanceof Enquiry) {
            $phone = $model->whatsapp ?: $model->parent_mobile;
            $enquiryId = $model->id;
            $course = $model->class;
        } elseif ($model instanceof Admission) {
            $phone = $model->contact;
            $enquiryId = $model->enquiry_id;
            $course = $model->class;
        } elseif ($model instanceof FollowUp) {
            $enquiry = $model->enquiry;
            if (!$enquiry) return null;
            $phone = $enquiry->whatsapp ?: $enquiry->parent_mobile;
            $enquiryId = $enquiry->id;
            $course = $enquiry->class;
        } else {
            return null;
        }

        if (!$phone) return null;
        $phone = $this->formatPhoneNumber($phone);

        // Construct Parent Name dynamically
        $parentName = $this->getParentName($model);

        // Map messages based on type
        switch ($type) {
            case 'new_enquiry':
                $message = "Hello {$parentName},

Greetings from StudyFlow Classes!

Thank you for your interest in our courses. We specialize in guiding students towards success in JEE/NEET and other competitive exams.

Our academic counselor would be happy to assist you with:
• Course details
• Fee structure
• Batch timings
• Admission process

Please let us know a convenient time to connect, or feel free to call us directly.

We look forward to helping your child achieve their goals!";
                break;

            case 'follow_up_scheduled':
                $date = $model->followup_date;
                $time = $model->followup_time;
                $message = "Hello {$parentName}, a follow-up discussion for your child at {$instituteName} for {$course} is scheduled on {$date} at {$time}. We look forward to speaking with you.";
                break;

            case 'reschedule':
                $date = $model->followup_date;
                $time = $model->followup_time;
                $message = "Hello {$parentName}, the follow-up for {$course} has been rescheduled to {$date} at {$time}. Thank you, {$instituteName}.";
                break;

            case 'confirmation':
                $message = "Hello {$parentName}, congratulations! The admission process for your child at {$instituteName} for {$course} has been initiated. Our team will guide you with the next steps.";
                break;

            case 'fee_reminder':
                $amount = $extra['amount'] ?? ($model->pending_amount ?? 0);
                $message = "Hello {$parentName}, this is a reminder regarding the pending fees of ₹" . number_format($amount, 2) . " for {$course} at {$instituteName}. Please process the payment at your earliest convenience.";
                break;

            case 'reminder':
                $message = "Hello {$parentName}, just a reminder regarding the enquiry for {$course}. Please feel free to reach out for any questions. Thank you, {$instituteName}.";
                break;

            default:
                Log::warning("Unknown WhatsApp trigger type: {$type}");
                return null;
        }

        return [
            'phone' => $phone,
            'message' => $message,
            'enquiry_id' => $enquiryId
        ];
    }

    /**
     * Construct Parent Name dynamically using middle_name and surname
     */
    protected function getParentName($model)
    {
        $enquiry = null;
        if ($model instanceof Enquiry) {
            $enquiry = $model;
        } elseif ($model instanceof Admission) {
            $enquiry = $model->enquiry;
        } elseif ($model instanceof FollowUp) {
            $enquiry = $model->enquiry;
        }

        if (!$enquiry) {
            return 'Parent';
        }

        $middleName = trim($enquiry->middle_name ?? '');
        $surname = trim($enquiry->surname ?? '');

        // Construct name combine: middle_name + space + surname
        $parentName = trim($middleName . ' ' . $surname);

        // Validation & Formatting
        // 1. Remove extra spaces
        $parentName = preg_replace('/\s+/', ' ', $parentName);

        // 2. Fallback if empty or numeric-only
        if (empty($parentName) || preg_match('/^[0-9\s]+$/', $parentName)) {
            return 'Parent';
        }

        // 3. Return directly
        return $parentName;
    }

    /**
     * Format phone number to international format: +91XXXXXXXXXX
     */
    protected function formatPhoneNumber($phone)
    {
        // Remove all non-numeric characters
        $clean = preg_replace('/[^0-9]/', '', $phone);
        
        // If it starts with 0, remove it
        if (str_starts_with($clean, '0')) {
            $clean = substr($clean, 1);
        }

        // If it starts with 91 and has 12 digits, it's already got the country code
        if (str_starts_with($clean, '91') && strlen($clean) === 12) {
            return '+' . $clean;
        }

        // Default to India (+91) if 10 digits
        if (strlen($clean) === 10) {
            return '+91' . $clean;
        }

        // Otherwise return with + if not already present (simplified)
        return '+' . $clean;
    }
}
