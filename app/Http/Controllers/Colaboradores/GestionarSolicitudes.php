<?php

namespace App\Http\Controllers\Colaboradores;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\RechazoSolicitud;
use App\Mail\SolicitudAprobada;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Traits\Paginador as PaginadorTrait;
use App\Services\colaboradores\GestionarSolicitudes as  GestionarSolicitudesService;

class GestionarSolicitudes extends Controller
{
    Use PaginadorTrait;
    private $tablaEmpleados;
    private $tablaSolicitudes;
    private $tablaSolicitudesDetalle;
    private $servicio;


    public function __construct(GestionarSolicitudesService $servicio)
    {
        $this->tablaEmpleados = 'empleados';
        $this->tablaSolicitudes = 'solicitud_vacaciones';
        $this->tablaSolicitudesDetalle = 'solicitud_vacaciones_detalle';
        $this->servicio = $servicio;
    }

    public function index()
    {
        return view('dashboard.colaboradores.submodulos.gestionarSolicitudes');
    }


    //Con este metodo llenamos la datatable del modulo /colaboradores/gestionarSolicitudes
    public function getSolicitudes(Request $request)
    {
        //En este arreglo vamos almacenar todos los datos que necesito para que la paginacion funcione correctamente
        $pagination = array(
            'value' => $request->input('search.value'),
            'start' => $request->input('start'),
            'length' => $request->input('length'),
            'draw' =>  $request->input('draw')
        );

        if(!empty($pagination['value']))
        {
            return $this->servicio->getSolicitudesFiltradas($pagination);

        }else{

            return $this->servicio->getSolicitudesAll($pagination);

        }

    }

    /*
        Genero el detallado de fecha para mostrarlas en el modal dentro del modulo de
        gestionarSolicitudes.
    */
    public function getSolicitudUser(Request $request)
    {
        return $this->servicio->getDetailVacation($request->input('id'));
    }


    public function aprobarSolicitud(Request $request)
    {
        try {
            return $this->servicio->aprobarSolicitud($request->input('id'));
        } catch (Exception $th) {
            Log::error('Error en método aprobar solicitud ' . $th->getMessage());
            return response('Tuvimos problemas al actualizar la solicitud Error:001 '  , 500);
        }
    }


    public function rechazarSolicitud(Request $request)
    {
        try {
            return $this->servicio->rechazarSolicitud($request->input('id') , $request->input('motivo'));
        } catch (Exception $th) {
            Log::error('Error en método rechazar solicitud ' . $th->getMessage());
            return response('Tuvimos problemas al actualizar la solicitud Error:002 '  , 500);
        }

    }
}
