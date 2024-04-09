<?php
 namespace App\Services\Correos;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\EnvioCorreo as EnvioCorreoMail;
use App\repositories\correos\SolicitudRepository;

 class ComprobarSolicitudes{

    private $repositorio;

    public function __construct(SolicitudRepository $repositorio)
    {
        $this->repositorio = $repositorio;
    }

    /**
     * Este servicio lo que hace es leer todas las solicitudes que tengan
     * un estatus como pendientes de aprobar, posteriormente
     *
     * @return void
     */
    public function comprobar()
    {
        $data = $this->repositorio->leerSolicitudes('Pendiente');
        $tomorrow = Carbon::createFromFormat('d/m/Y', Carbon::now()->format('d/m/Y'))->addDay();

        foreach ($data as $registro)
        {
            $fechaAux = Carbon::createFromFormat('Y-m-d', $registro->fechaInicio);
           // echo ++$contador . '<br>';

            if($fechaAux->eq($tomorrow))
            {
               //Aqui debemos hacer una query donde obtengamos el correo del jefe inmediato, para poder
               // obtener su correo y poderle enviar un mensaje de recordatorio indicandole
               //Que necesita revisar la solicitud de su subordinado.
                $empleado = DB::table('empleados as e1')
                ->join('empleados as e2' , 'e2.idJefe' , '=' , 'e1.id')
                ->where('e2.id' , '=' , $registro->id_empleado)
                ->select('e1.correo' , 'e2.colaborador as nombreEmpleado' , 'e1.colaborador as nombreJefe')
                ->first();

                Mail::to('antonio.astudillo@univer-gdl.edu.mx')->queue(new EnvioCorreoMail( $empleado->nombreJefe , $empleado->nombreEmpleado , Carbon::parse($registro->fechaInicio)->format('d/m/Y') ));

            }

        }
    }

 }
