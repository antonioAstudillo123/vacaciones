<?php

namespace App\Http\Controllers\RH;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ConsultarSolicitudes extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $anioActual = date('Y');

        $resultados = DB::table('solicitud_vacaciones as sv')
            ->join('empleados as e', 'e.id', '=', 'sv.id_empleado')
            ->where('sv.estatus', '=', 'Aprobada')
            ->whereYear('sv.fecha', '=', $anioActual)
            ->groupBy('sv.id_empleado')
            ->selectRaw('SUM(sv.dias) as diasTomados, e.numeroEmpleado , e.fechaIngreso')
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

       // return $resultado;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
