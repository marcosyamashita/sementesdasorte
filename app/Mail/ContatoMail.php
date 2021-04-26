<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ContatoMail extends Mailable
{
    use Queueable, SerializesModels;
    public $dados;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(array $dados)
    {
        $this->dados = $dados;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($this->dados['email'])
                    ->subject('Contato de Cliente - Sementes da Sorte')
                    ->view('email')
                    ->with($this->dados);
    }
}
