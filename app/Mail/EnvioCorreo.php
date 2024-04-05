<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;


/**}
 * Esta clase la voy a usar para enviar un correo a un colaborador dentro del sistema
 */

class EnvioCorreo extends Mailable
{
    use Queueable, SerializesModels;

    public $nombreJefe;
    public $nombreEmpleado;
    public $fechaInicio;


    /**
     * Create a new message instance.
     */
    public function __construct($nombreJefe , $nombreEmpleado , $fechaInicio)
    {
        $this->nombreJefe = $nombreJefe;
        $this->nombreEmpleado = $nombreEmpleado;
        $this->fechaInicio = $fechaInicio;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Recordatorio: Solicitud de Vacaciones Pendiente de Aprobación',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'dashboard.mails.solicitudSinAprobar',
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
