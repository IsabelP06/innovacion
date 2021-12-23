<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ModificacionEntregaArchivos extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $registro;
    public $guia_venta;
    public $guia_cobranza;
    public $grr;
    public function __construct($registro, $pdf1, $pdf2,$grr)
    {
        $this->registro = $registro;
        $this->guia_venta = $pdf1;
        $this->guia_cobranza = $pdf2;
        $this->grr=$grr;
    }
    public function build()
    {
        if ($this->guia_venta && $this->guia_cobranza) {
            return $this->view('template_email.modificacionentregaguias', ["registro" => $this->registro,"grr"=>$this->grr])->subject("Modificacion de entrega GRR")->attach($this->guia_venta)->attach($this->guia_cobranza);
        } 
        return $this->view('template_email.modificacionentregaguias', ["registro" => $this->registro,"grr"=>$this->grr])->subject("Modificacion de entrega GRR");
    }
}
