<?php

namespace App\Http\Controllers\RH;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ConsultarSolicitudes extends Controller
{
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

        $resultados = DB::table('empleados as e')
            ->select('e.id', 'e.numeroEmpleado', 'e.colaborador', 'e.fechaIngreso', DB::raw('SUM(sv.dias) AS diasTomados'))
            ->leftJoin('solicitud_vacaciones AS sv', function($join) use ($anioActual) {
                $join->on('e.id', '=', 'sv.id_empleado')
                    ->where('sv.estatus', '=', 'Aprobada')
                    ->whereYear('sv.fecha', '=', $anioActual);
            })
            ->groupBy('e.id', 'e.numeroEmpleado', 'e.colaborador', 'e.fechaIngreso')
            ->orderBy('e.colaborador')
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


            if($resultado === NULL)
            {
                $item->diasRestante = 0;
                $item->diasTomados = 0;
            }
            else
            {
                if($item->diasTomados === null)
                {
                    $item->diasTomados = 0;
                }

                $item->diasRestante = (int)$resultado->dias - (int)$item->diasTomados;

            }

            $item->anioActual = $anioActual;
        }

        return response()->json([
            'data' => $resultados,
        ]);

    }

    public function reporteEmpleado(Request $request)
    {
        $anioActual = date('Y');
        $idEmpleado = $request->input('id');

        $resultados = DB::table('empleados as e')
        ->select('e.id', 'e.numeroEmpleado', 'e.colaborador', 'e.fechaIngreso', DB::raw('SUM(sv.dias) AS diasTomados'))
        ->leftJoin('solicitud_vacaciones AS sv', function($join) use ($anioActual) {
            $join->on('e.id', '=', 'sv.id_empleado')
                ->where('sv.estatus', '=', 'Aprobada')
                ->whereYear('sv.fecha', '=', $anioActual);
        })
        ->where('e.id', '=', $idEmpleado)
        ->groupBy('e.id', 'e.numeroEmpleado', 'e.colaborador', 'e.fechaIngreso')
        ->orderBy('e.colaborador')
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


            if($resultado === NULL)
            {
                $item->diasRestante = 0;
                $item->diasTomados = 0;
            }
            else
            {
                if($item->diasTomados === null)
                {
                    $item->diasTomados = 0;
                }

                $item->diasRestante = (int)$resultado->dias - (int)$item->diasTomados;

            }

            $item->anioActual = $anioActual;
        }

        return response()->json([
            'data' => $resultados,
        ]);
    }


}//cierra clase
