<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment;


class ChargePaymentMail extends Mailable
{
    use Queueable, SerializesModels;

    public $mailData;
    public $subject;
    public $pdf;  // Tambahkan properti ini
    public $pdfReport;  // Tambahkan properti ini jika diperlukan

    /**
     * Create a new message instance.
     */
    public function __construct($mailData, $subject, $pdf, $pdfReport = null)  // Tambahkan parameter PDF
    {
        $this->mailData = $mailData;
        $this->subject = $subject;
        $this->pdf = $pdf;  // Assign PDF
        $this->pdfReport = $pdfReport;  // Assign PDF Report jika ada
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
            view: 'emails.spp-mail',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $file = [];

        // Tambahkan attachment PDF jika ada
        if ($this->pdf) {
            $file[] = Attachment::fromData(
                fn() => $this->pdf->output(),
                'Charge Payment Invoice - ' . $this->mailData['bill'][0]->type . ' ' . date('F Y', strtotime($this->mailData['bill'][0]->created_at)) . ' ' . $this->mailData['student']->name
            )->withMime('application/pdf');
        }

        // Tambahkan PDF Report jika ada
        if ($this->pdfReport) {
            $file[] = Attachment::fromData(
                fn() => $this->pdfReport->output(),
                'Charge Payment Report - ' . $this->mailData['bill'][0]->type . ' ' . date('F Y', strtotime($this->mailData['bill'][0]->deadline_in)) . ' ' . $this->mailData['student']->name
            )->withMime('application/pdf');
        }

        return $file;
    }
}
