<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PedirGuias extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $registro;
    public $guiaspendientes;
    public function __construct($registro,$guiaspendientes)
    {
            $this->registro = $registro;
            $this->guiaspendientes = $guiaspendientes;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('template_email.pedirguias',["registro"=>$this->registro,"guiaspendientes"=>$this->guiaspendientes])->subject("Enviar GRR");
    }
}
