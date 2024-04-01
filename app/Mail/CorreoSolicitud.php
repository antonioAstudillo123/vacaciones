<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CorreoSolicitud extends Mailable
{
    use Queueable, SerializesModels;
    public $nombreJefe;
    public $fechaInicio;
    public $fechaFin;
    public $nombreEmpleado;


    /**
     * $data es un arreglo el cual trae las fechas de los días de que el usuario
     * pidio de vacaciones
     */
    public function __construct($nombreJefe , $nombreEmpleado , $data)
    {
        $this->nombreJefe = $nombreJefe;
        $this->nombreEmpleado = $nombreEmpleado;
        $this->fechaInicio =   date("d-m-Y", strtotime(reset($data)));
        $this->fechaFin = date("d-m-Y", strtotime(end($data)));
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Correo Solicitud Vacaciones',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'dashboard.mails.solicitudCorreo',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
