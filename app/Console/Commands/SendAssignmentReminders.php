<?php

namespace App\Console\Commands;

use App\Models\Admission;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendAssignmentReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assignments:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send assignment reminders before, on, and after the due date';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting assignment reminders...');

        // 1. Before due date (due tomorrow)
        $this->processReminders(
            Carbon::tomorrow()->toDateString(), 
            'assignment_reminder_before',
            'Reminder: Assignment due tomorrow',
            'Your assignment "%s" for subject %s is due tomorrow.'
        );

        // 2. On due date (due today)
        $this->processReminders(
            Carbon::today()->toDateString(), 
            'assignment_reminder_on',
            'Reminder: Assignment due today',
            'Your assignment "%s" for subject %s is due today.'
        );

        // 3. After due date (due yesterday)
        $this->processReminders(
            Carbon::yesterday()->toDateString(), 
            'assignment_reminder_after',
            'Overdue: Assignment deadline passed',
            'Your assignment "%s" for subject %s was due on %s.'
        );

        $this->info('Assignment reminders complete.');
    }

    private function processReminders($date, $type, $title, $messageTemplate)
    {
        $assignments = Assignment::whereDate('due_date', $date)->get();

        if ($assignments->isEmpty()) {
            return;
        }

        foreach ($assignments as $assignment) {
            $studentEmails = Admission::where('class', $assignment->class_id)
                ->orWhere('class', 'like', $assignment->class_id . '-%')
                ->pluck('email')
                ->unique();

            $users = User::whereIn('email', $studentEmails)->get();

            foreach ($users as $user) {
                // Check if already submitted
                $hasSubmitted = AssignmentSubmission::where('assignment_id', $assignment->id)
                    ->where('student_id', $user->id)
                    ->exists();

                if ($hasSubmitted) {
                    continue;
                }

                // Check if reminder already sent to prevent duplicates
                $alreadySent = Notification::where('user_id', $user->id)
                    ->where('type', $type)
                    ->whereJsonContains('data->assignment_id', $assignment->id)
                    ->exists();

                if (!$alreadySent) {
                    $formattedMessage = sprintf(
                        $messageTemplate, 
                        $assignment->title, 
                        $assignment->subject, 
                        $assignment->due_date->format('M d, Y')
                    );

                    Notification::create([
                        'user_id' => $user->id,
                        'sender_id' => $assignment->teacher_id,
                        'title' => $title,
                        'message' => $formattedMessage,
                        'type' => $type,
                        'data' => [
                            'redirect_url' => route('student.assignments'),
                            'assignment_id' => $assignment->id
                        ]
                    ]);
                }
            }
        }
    }
}
