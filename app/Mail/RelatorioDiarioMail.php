<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;

class RelatorioDiarioMail extends Mailable
{
    use Queueable, SerializesModels;

    public $dados;
    public $pdfContent;

    public function __construct($dados, $pdfContent)
    {
        $this->dados = $dados;
        $this->pdfContent = $pdfContent;
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.relatorio_diario', // Tens de criar esta view simples
        );
    }

    public function attachments(): array
    {
        return [
            Attachment::fromData(fn () => $this->pdfContent, 'Relatorio_Diario.pdf')
                ->withMime('application/pdf'),
        ];
    }
}