<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\WhatsAppLog;

class SendWhatsAppMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $phone;
    public $message;
    public $enquiryId;
    public $triggerType;
    
    /**
     * The number of times the job may be attempted.
     */
    public $tries = 3;

    /**
     * Create a new job instance.
     */
    public function __construct($phone, $message, $enquiryId = null, $triggerType = null)
    {
        $this->phone = $phone;
        $this->message = $message;
        $this->enquiryId = $enquiryId;
        $this->triggerType = $triggerType;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $token = env('WHATSAPP_API_TOKEN');
        $phoneNumberId = env('PHONE_NUMBER_ID');
        $baseUrl = env('WHATSAPP_API_URL', 'https://graph.facebook.com/v17.0');

        if (!$token || !$phoneNumberId) {
            $this->logFailure('Credentials missing in .env');
            return;
        }

        $url = "{$baseUrl}/{$phoneNumberId}/messages";

        try {
            $response = Http::withToken($token)->post($url, [
                'messaging_product' => 'whatsapp',
                'to' => $this->phone,
                'type' => 'text',
                'text' => [
                    'body' => $this->message,
                    'preview_url' => false
                ]
            ]);

            if ($response->successful()) {
                $this->logSuccess();
            } else {
                $this->logFailure($response->body());
                // Mark for retry if it's a transient error (e.g. rate limit, though Meta handles that)
                if ($response->status() >= 500) {
                    $this->release(60);
                }
            }
        } catch (\Exception $e) {
            $this->logFailure($e->getMessage());
            $this->release(120);
        }
    }

    protected function logSuccess()
    {
        WhatsAppLog::create([
            'enquiry_id' => $this->enquiryId,
            'phone' => $this->phone,
            'message' => $this->message,
            'status' => 'sent',
            'trigger_type' => $this->triggerType,
        ]);
        Log::info("WhatsApp message sent to {$this->phone}");
    }

    protected function logFailure($error)
    {
        WhatsAppLog::create([
            'enquiry_id' => $this->enquiryId,
            'phone' => $this->phone,
            'message' => $this->message,
            'status' => 'failed',
            'error_message' => $error,
            'trigger_type' => $this->triggerType,
        ]);
        Log::error("WhatsApp message failed to {$this->phone}: {$error}");
    }
}
