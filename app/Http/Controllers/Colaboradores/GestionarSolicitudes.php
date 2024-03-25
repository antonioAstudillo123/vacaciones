<?php

namespace App\Http\Controllers\Colaboradores;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Traits\Paginador as PaginadorTrait;

class GestionarSolicitudes extends Controller
{
    Use PaginadorTrait;
    private $tablaEmpleados;
    private $tablaSolicitudes;
    private $tablaSolicitudesDetalle;


    public function __construct()
    {
        $this->tablaEmpleados = 'empleados';
        $this->tablaSolicitudes = 'solicitud_vacaciones';
        $this->tablaSolicitudesDetalle = 'solicitud_vacaciones_detalle';
    }

    public function index()
    {
        return view('dashboard.colaboradores.submodulos.gestionarSolicitudes');
    }


    public function getSolicitudes(Request $request)
    {
        $idJefe = Auth::user()->id;
        $busqueda = $request->input('search.value');

        $empleado = DB::table($this->tablaEmpleados)
        ->where('idUser', '=', $idJefe)
        ->get();

        if(!empty($busqueda))
        {
            $busqueda = $request->input('search.value');


            $query = DB::table('solicitud_vacaciones as sv')
                ->select('sv.id', DB::raw("DATE_FORMAT(sv.fecha, '%d-%m-%Y  -- %H:%i:%s %p') AS fecha"), 'sv.dias', 'sv.estatus', 'e.colaborador')
                ->join('empleados as e', 'e.id', '=', 'sv.id_empleado')
                ->where('e.idJefe', $empleado[0]->id)
                    ->where(function ($query) use ($busqueda) {
                        $query->where('sv.fecha', 'LIKE', '%' . $busqueda . '%')
                            ->orWhere('e.numeroEmpleado', 'LIKE', '%' . $busqueda . '%')
                            ->orWhere('e.colaborador', 'LIKE', '%' . $busqueda . '%')
                            ->orWhere('sv.estatus', 'LIKE', '%' . $busqueda . '%')
                            ->orWhere('e.fechaIngreso', 'LIKE', '%' . $busqueda . '%');
                    });

                $this->inicializarAtributos($request , $query);
                $this->paginarBusqueda();
        }else{


            $query = DB::table('solicitud_vacaciones as sv')
                ->select('sv.id', DB::raw("DATE_FORMAT(sv.fecha, '%d-%m-%Y -- %H:%i:%s %p') AS fecha"), 'sv.dias', 'sv.estatus', 'e.colaborador')
                ->join('empleados as e', 'e.id', '=', 'sv.id_empleado')
                ->where('e.idJefe', $empleado[0]->id);

            $this->inicializarAtributos($request , $query);
            $this->paginarTotal();
        }

        return $this->respuesta();

    }


    /*

            Genero el detallado de fecha para mostrarlas en el modal dentro del modulo de
            gestionarSolicitudes
    */
    public function getSolicitudUser(Request $request)
    {
        $idSolicitud = $request->input('id');

        try {
            $resultado = DB::table($this->tablaSolicitudesDetalle )
            ->select('id' , DB::raw("DATE_FORMAT(fecha, '%d-%m-%Y') AS fecha") , DB::raw("DATE_FORMAT(fecha, '%Y-%m-%d %H:%i:%s %p') AS dia"))
            ->orderBy('fecha')
            ->where('id_solicitud' , '=' , $idSolicitud)
            ->get();
        } catch (Exception $th) {
            return response('Tuvimos problemas al procesar la solicitud'  , 500);
        }

        return response()->json(['data' => $resultado]);

    }


    public function aprobarSolicitud(Request $request)
    {
        try {
            DB::table($this->tablaSolicitudes)
              ->where('id', $request->input('id'))
              ->update(['estatus' => 'Aprobada']);
        } catch (Exception $th) {
            return response('Tuvimos problemas al actualizar la solicitud' , 500);
        }

        return response('Solicitud aprobada con éxito' , 200);
    }


    public function rechazarSolicitud(Request $request)
    {
        try {
            DB::table($this->tablaSolicitudes)
              ->where('id', $request->input('id'))
              ->update(
                [
                    'estatus' => 'Rechazada',
                    'observaciones' => Str::ucfirst($request->input('motivo'))

                ]
            );
        } catch (Exception $th) {
            return response('Tuvimos problemas al actualizar la solicitud', 500);
        }

        return response('Solicitud rechazada con éxito' , 200);
    }
}
