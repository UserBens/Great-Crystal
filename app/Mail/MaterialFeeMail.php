<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment;

class MaterialFeeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $mailData;
    public $subject;
    public $pdf;
    /**
     * Create a new message instance.
     */
    public function __construct($mailData, $subject, $pdf)
    {
        $this->mailData = $mailData;
        $this->subject = $subject;
        $this->pdf = $pdf;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subject,
        );
    }
    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'components.student.materialfee.email', // View untuk isi email
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
            Attachment::fromData(fn() => $this->pdf->output(), 'Material Fee ' . date('F Y', strtotime($this->mailData['bill'][0]->created_at)) . ' ' . $this->mailData['student']->name)
                ->withMime('application/pdf'),
        ];
    }
}
