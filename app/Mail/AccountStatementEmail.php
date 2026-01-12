<?php

namespace App\Mail;

use App\Models\Student;
use App\Traits\PDF_forms;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Mpdf\Mpdf;

class AccountStatementEmail extends Mailable
{
    use Queueable, SerializesModels, PDF_forms;
    public Student $student;
    public $account_statement;
    /**
     * Create a new message instance.
     */
    public function __construct(Student $student)
    {
        $this->student = $student;
        $this->account_statement = $this->account_statement($this->student)->Output('', 'S');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            cc: 'info@alordashley.com',
            subject: 'Account Statement Email',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.account_statement',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [
            Attachment::fromData(fn () => $this->account_statement,  'AccountStatement.pdf')->withMime('application/pdf'),
        ];
    }
}
