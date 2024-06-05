<?php

namespace App\Http\Controllers\Colaboradores;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Services\colaboradores\GestionarSolicitudes as  GestionarSolicitudesService;

class GestionarSolicitudes extends Controller
{

    private $servicio;

    public function __construct(GestionarSolicitudesService $servicio)
    {
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
