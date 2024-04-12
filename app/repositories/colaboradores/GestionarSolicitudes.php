<?php

namespace app\repositories\colaboradores;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Traits\Paginador as PaginadorTrait;


class GestionarSolicitudes
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
                                     ->orWhere('sv.dias', 'LIKE', '%' . $busqueda . '%')
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




}
