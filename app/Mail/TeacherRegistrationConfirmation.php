<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TeacherRegistrationConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $teacherName;
    public $email;
    public $department;
    public $assignedClasses;
    public $assignedSubjects;

    /**
     * Create a new message instance.
     */
    public function __construct($teacherName, $email, $department, $assignedClasses, $assignedSubjects)
    {
        $this->teacherName = $teacherName;
        $this->email = $email;
        $this->department = $department;
        $this->assignedClasses = $assignedClasses;
        $this->assignedSubjects = $assignedSubjects;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Teacher Registration Successful - StudyFlow Classes',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.teacher-registration-confirmation',
            with: [
                'teacherName' => $this->teacherName,
                'email' => $this->email,
                'department' => $this->department,
                'assignedClasses' => $this->assignedClasses,
                'assignedSubjects' => $this->assignedSubjects,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
