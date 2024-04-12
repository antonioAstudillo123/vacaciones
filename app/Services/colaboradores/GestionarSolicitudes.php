<?php

namespace App\Services\colaboradores;

use App\Traits\Auxiliares;
use App\Mail\RechazoSolicitud;
use App\Mail\SolicitudAprobada;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Traits\Paginador as PaginadorTrait;
use App\repositories\colaboradores\GestionarSolicitudes as GestionarSolicitudesRepository;

class GestionarSolicitudes{
    Use PaginadorTrait;
    Use Auxiliares;
    private $tablaEmpleados;
    private $tablaSolicitudes;
    private $tablaSolicitudesDetalle;
    private $repositorio;

    public function __construct(GestionarSolicitudesRepository $repository)
    {
        $this->tablaEmpleados = 'empleados';
        $this->tablaSolicitudes = 'solicitud_vacaciones';
        $this->tablaSolicitudesDetalle = 'solicitud_vacaciones_detalle';
        $this->repositorio = $repository;
    }

    //Con este metodo llenamos la datatable del modulo /colaboradores/gestionarSolicitudes
    public function getSolicitudesFiltradas($dataPagination)
    {
        //Obtenemos el id del usuario logueado, que en teoría es el jefe del area el cual quiere
        //consultar las solicitudes que han realizado sus subordinados para procesarlas.
        $idJefe = Auth::user()->id;

        //Obtenemos el empleado asociado al user que esta autenticado usando un metodo generico del trait Auxiliares
        $empleado = $this->getEmpleado($idJefe);

        return $this->repositorio->getSolicitudesFiltradas($dataPagination ,  $empleado[0]->id);

    }


    /**
        * Este metodo lo usamos en casos de que no se envie ningun valor en el input search, lo que significa que no hay filtros
          por lo tanto, se tendrá que mandar todos los registros, pero paginados.
        *
        * @param [type] $dataPagination
        * @return void
    */
    public function getSolicitudesAll($dataPagination)
    {
        //Obtenemos el id del usuario logueado, que en teoría es el jefe del area el cual quiere
        //consultar las solicitudes que han realizado sus subordinados para procesarlas.
        $idJefe = Auth::user()->id;

        //Obtenemos el empleado asociado al user que esta autenticado usando un metodo generico del trait Auxiliares
        $empleado = $this->getEmpleado($idJefe);

        return $this->repositorio->getSolicitudesAll($dataPagination ,  $empleado[0]->id);
    }

    /**
        * Obtenemos el detallato de fechas que solicito un colaborador. Este metodo se manda a llamar cuando el usuario
        con perfil de jefe presiona sobre la opcion de detalle
        */

    public function getDetailVacation($idSolicitud)
    {
        return $this->repositorio->getDetailVacation($idSolicitud);
    }



    /**
     * Este metodo lo uso para aprobar una solicitud, El objetivo es cambiar el estado de la solicitud que se esta aprobando
     * y enviarle un correo al empleado indicandole que su solicitud fue aprobada
     */


     public function aprobarSolicitud($idSolicitud)
     {
        DB::beginTransaction();

        try
        {
            $this->repositorio->setEstatusSolicitud('Aprobada' , $idSolicitud , 'Solicitud aprobada');
            $empleado = $this->repositorio->getDataEmpleado($idSolicitud);
            $dias = $this->repositorio->getFechas($idSolicitud);

            //Enviamos un correo
            Mail::to('antonioastudillo206@gmail.com')->queue(new SolicitudAprobada($empleado->colaborador , $dias[0]->fecha ,  $dias[count($dias)-1]->fecha ));
            // Mail::to($empleado->correo)->queue(new SolicitudAprobada($empleado->colaborador , $dias[0]->fecha ,  $dias[count($dias)-1]->fecha ));
            DB::commit();

         } catch (Exception $th)
         {
            DB::rollback();
            Log::error('Error en método aprobar solicitud ' . $th->getMessage());
            return response('Tuvimos problemas al actualizar la solicitud' , 500);
         }

        return response('Solicitud aprobada con éxito' , 200);
     }


     public function rechazarSolicitud($idSolicitud , $motivo)
     {
        DB::beginTransaction();

        try
        {

            $this->repositorio->setEstatusSolicitud('Rechazada' , $idSolicitud , $motivo);
            $empleado = $this->repositorio->getDataEmpleado($idSolicitud);

            Mail::to('antonioastudillo206@gmail.com')->queue(new RechazoSolicitud( $empleado->colaborador  , $motivo));
           // Mail::to($empleado->correo)->queue(new RechazoSolicitud( $empleado->colaborador  , $motivo));

            DB::commit();

        } catch (Exception $th) {
             DB::rollback();
             Log::error('Error al enviar correo: ' . $th->getMessage());

             return response('Tuvimos problemas al actualizar la solicitud', 500);
        }

        return response('Solicitud rechazada con éxito' , 200);
     }
}
