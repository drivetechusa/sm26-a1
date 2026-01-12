<?php

namespace App\Mail;

use App\Models\Letter;
use App\Models\Student;
use App\Traits\PDF_forms;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CompletionCertificate extends Mailable
{
    use Queueable, SerializesModels, PDF_forms;

    public Student $student;
    public $completion_certificate;
    public $message;
    /**
     * Create a new message instance.
     */
    public function __construct(Student $student, Letter $letter)
    {
        $this->student = $student;
        $this->message = $letter->body;
        $this->completion_certificate = $this->completion_cert($this->student)->Output('', 'S');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            cc: config('app.school_email'),
            subject: 'Completion Certificate',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.completion_certificate',
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
            Attachment::fromData(fn () => $this->completion_certificate,  'CompletionCertificate.pdf')->withMime('application/pdf'),
        ];
    }
}
