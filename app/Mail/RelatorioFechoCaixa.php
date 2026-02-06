<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;

class RelatorioFechoCaixa extends Mailable
{
    use Queueable, SerializesModels;

    public $dados;
    public $path;

    public function __construct($dados, $path)
    {
        $this->dados = $dados;
        $this->path = $path;
    }

    public function build()
    {
        return $this->subject('Relatório de Fecho de Caixa - SGB Elite')
                    ->view('emails.relatorio_fecho')
                    ->attach($this->path, [
                        'as' => 'relatorio_diario.pdf',
                        'mime' => 'application/pdf',
                    ]);
    }
}