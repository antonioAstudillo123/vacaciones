<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        //Aqui debemos hacer querys para obtener la data que iremos mostrando en el sistema
        //en el apartado de aceptadas, pendientes, rechazadas
        $user = Auth::user();
        $role = $user->getRoleNames()->first();

        $idUser = $user->id;

        $empleado = DB::table('empleados')
                 ->where('idUser', '=', $idUser)
                 ->get();


        switch($role)
        {
            case'administrador':

                break;
            case'colaborador':

            //Generamos la fecha en la que el empleado ingreso a trabajar
            $fechaInicio = Carbon::createFromFormat('Y-m-d',$empleado[0]->fechaIngreso);

            //Generamos la fecha actual para poder crear la operacion de la diferencia de años
            $fechaFin = Carbon::createFromFormat('d/m/Y', Carbon::now()->format('d/m/Y'));

            //Obtenemos la diferencia de años que existe entre el año en el que el usuario ingreso y el año actual
            $diffYears = $fechaInicio->diffInYears($fechaFin);


            $diasVacaciones = DB::table('dias_vacaciones')
                ->select('dias')
                ->where('anios', '=', $diffYears)
                ->get();


               $resultado = DB::table('solicitud_vacaciones')
                    ->select(DB::raw('sum(dias) as totalDias'))
                    ->where('id_empleado', $empleado[0]->id)
                    ->where('estatus', 'Aprobada')
                    ->first();


                if(isset($diasVacaciones[0]->dias))
                {
                    $diasDisponibles = $diasVacaciones[0]->dias - $resultado->totalDias;
                }else{
                    $diasDisponibles = 0;
                }




                    return view('home' , ['role' => $role , 'diasDisponibles' => $diasDisponibles , 'diasUtilizados' => $resultado->totalDias ? $resultado->totalDias : 0 ]);


                break;
            case'humanos':

            break;

        }


        return view('home' , ['role' => $role ]);


    }
}
