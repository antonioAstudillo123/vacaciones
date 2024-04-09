<?php

namespace App\Console\Commands;

use Exception;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Services\Correos\ComprobarSolicitudes;

class EnvioCorreo extends Command
{

    private $servicio;

    public function __construct(ComprobarSolicitudes $servicio)
    {
        parent::__construct();
        $this->servicio = $servicio;
    }
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
            $this->servicio->comprobar();
        } catch (Exception $th){
            Log::error('Error al enviar correo: ' . $th->getMessage());
        }
    }
}
