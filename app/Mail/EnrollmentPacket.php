<?php

namespace App\Mail;

use App\Enums\StudentTypes;
use App\Models\Letter;
use App\Models\Seminar;
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

class EnrollmentPacket extends Mailable
{
    use Queueable, SerializesModels, PDF_forms;

    public Seminar $seminar;
    public Student $student;
    public $contract;
    public $medform;
    public $statement;
    public $message;
    /**
     * Create a new message instance.
     */
    public function __construct(Seminar $seminar, Student $student)
    {
        $this->seminar = $seminar;
        $this->student = $student;
        $this->contract = $this->selectContract($this->student, $this->seminar)->Output('', 'S');
        $this->medform = $this->med_form($this->student)->Output('', 'S');
        $this->statement = $this->account_statement($this->student)->Output('', 'S');

        switch ($student->type)
        {
            case StudentTypes::COURSE_A:
            case StudentTypes::COURSE_B:
            case StudentTypes::COURSE_C:
                $this->message = Letter::find(2)->body;
                $this->message = str_replace('?name?', $student->display_name, $this->message);
                $this->message = str_replace('?title?', $this->seminar->display_title, $this->message);
                break;
            case StudentTypes::POINT_REDUCTION:
            case StudentTypes::DIP:
                $this->message = Letter::find(4)->body;
                $this->message = str_replace('?title?', $seminar->display_title, $this->message);
                break;
        }

    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            cc: config('app.school_email'),
            bcc: 'jimbreen@alordashley.com',
            subject: 'Enrollment Packet',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.enrollment-packet',
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
            Attachment::fromData(fn () => $this->contract, $this->student->id . '-Contract.pdf')->withMime('application/pdf'),
            Attachment::fromData(fn () => $this->medform, $this->student->id . '-MedForm.pdf')->withMime('application/pdf'),
            Attachment::fromData(fn () => $this->statement, $this->student->id . '-AccountStatement.pdf')->withMime('application/pdf'),
        ];
    }
}
