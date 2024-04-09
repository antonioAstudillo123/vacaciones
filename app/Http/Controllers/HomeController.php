<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;
use App\Services\Correos\ComprobarSolicitudes;

class HomeController extends Controller
{
    private $servicio;

    public function __construct(ComprobarSolicitudes $servicio)
    {
        $this->middleware('auth');
        $this->servicio = $servicio;
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

    }


    //Metodo de prueba
    public function prueba()
    {
        $this->servicio->comprobar();
    }
}
