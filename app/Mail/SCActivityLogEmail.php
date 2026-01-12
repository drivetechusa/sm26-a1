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

class SCActivityLogEmail extends Mailable
{
    use Queueable, SerializesModels, PDF_forms;

    public Student $student;
    public $activity_log;
    /**
     * Create a new message instance.
     */
    public function __construct(Student $student)
    {
        $this->student = $student;
        $this->activity_log = $this->sc_activity_log($student)->Output('', 'S');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            cc: config('app.school_email'),
            subject: 'SC Activity Log Email',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.sc_activity_log',
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
            Attachment::fromData(fn () => $this->activity_log,  'ActivityLog.pdf')->withMime('application/pdf'),
        ];
    }
}
