<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentReceiptMail extends Mailable
{
    use Queueable, SerializesModels;

    public $payment;
    public $admission;
    public $enquiry;
    public $installmentNumber;
    public $remainingBalance;

    /**
     * Create a new message instance.
     */
    public function __construct($payment, $admission, $enquiry, $installmentNumber, $remainingBalance)
    {
        $this->payment = $payment;
        $this->admission = $admission;
        $this->enquiry = $enquiry;
        $this->installmentNumber = $installmentNumber;
        $this->remainingBalance = $remainingBalance;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Payment Receipt - ' . config('app.name', 'Institute'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.fees.receipt',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
