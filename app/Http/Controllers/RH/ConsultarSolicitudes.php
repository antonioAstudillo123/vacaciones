<?php

namespace App\Http\Controllers\RH;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Traits\Paginador as PaginadorTrait;

class ConsultarSolicitudes extends Controller
{
    Use PaginadorTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard.recursosHumanos.submodulos.historico');

    }


    /**
     * Este metodo nos va servir para obtener el listado de todas las solicitudes de vacciones que han realizado
     *
     * @param Request $request
     * @return void
     */
    public function all()
    {
        $anioActual = date('Y');

        $resultados = DB::table('solicitud_vacaciones as sv')
                ->join('empleados as e', 'e.id', '=', 'sv.id_empleado')
                ->where('sv.estatus', '=', 'Aprobada')
                ->whereYear('sv.fecha', '=', $anioActual)
                ->groupBy('sv.id_empleado')
                ->selectRaw('e.id , e.numeroEmpleado , e.colaborador  , e.fechaIngreso , SUM(sv.dias) as diasTomados ')
                ->get()
                ->toArray();


        foreach ($resultados as $item)
        {
            //Generamos la fecha en la que el empleado ingreso a trabajar
            $fechaInicio = Carbon::createFromFormat('Y-m-d',$item->fechaIngreso);

            //Generamos la fecha actual para poder crear la operacion de la diferencia de años
            $fechaFin = Carbon::createFromFormat('d/m/Y', Carbon::now()->format('d/m/Y'));

            //Obtenemos la diferencia de años que existe entre el año en el que el usuario ingreso y el año actual
            $diffYears = $fechaInicio->diffInYears($fechaFin);

            $resultado = DB::table('dias_vacaciones')
                ->select('dias')
                ->where('anios', '=', $diffYears)
                ->first();

            $item->diasRestante = (int)$resultado->dias - (int)$item->diasTomados;

        }

        return response()->json([
            'data' => $resultados,
        ]);

    }


}
