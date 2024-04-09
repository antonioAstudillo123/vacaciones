<?php

namespace App\Console\Commands;

use Exception;
use App\Mail\EnvioCorreo as EnvioCorreoMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EnvioCorreo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:envio-correo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

        // Aqui debe de ir toda la logica que voy aplicar para el envio de correo a jefes inmediatos
        // En caso de que una solicitud de un subordinado se encuentre en pendiente y el dia
        // de fecha de inicio de periodo vacacional este cerca.
    public function handle()
    {
        try
        {
            Mail::to('antonio.astudillo@univer-gdl.edu.mx')->send(new EnvioCorreoMail( 'Antonio Astudillo' , 'Oscar de la Hoya' , '05/04/2024'));
        } catch (Exception $th){
            Log::error('Error al enviar correo: ' . $th->getMessage());
        }
    }
}
