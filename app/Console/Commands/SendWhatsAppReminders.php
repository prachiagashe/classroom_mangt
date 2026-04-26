<?php

namespace App\Console\Commands;

use App\Models\FollowUp;
use Illuminate\Console\Command;
use Carbon\Carbon;

class SendWhatsAppReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'whatsapp:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send WhatsApp reminders for follow-ups scheduled for today';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::now()->toDateString();
        
        $this->info("Fetching follow-ups for {$today}");

        $followUps = FollowUp::with('enquiry')
            ->whereDate('followup_date', $today)
            ->get();

        if ($followUps->isEmpty()) {
            $this->info("No follow-ups scheduled for today.");
            return;
        }

        // Check credentials once
        $apiUrl = env('WHATSAPP_API_URL');
        $apiToken = env('WHATSAPP_API_TOKEN');

        if (!$apiUrl || !$apiToken) {
            $this->warn("WhatsApp API credentials not configured in .env. Automated messages will only be logged locally.");
        }

        foreach ($followUps as $followUp) {
            if (!$followUp->enquiry) continue;

            // Send WhatsApp message correctly using the centralized Service
            app(\App\Services\WhatsAppService::class)->sendMessage('reminder', $followUp);

            $this->info("Reminder queued for enquiry ID: {$followUp->enquiry_id}");
        }

        $this->info("All reminders have been queued.");
    }
}
