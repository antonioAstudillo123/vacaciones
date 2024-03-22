<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RegistroVacaciones extends Controller
{
    private $tablaEmpleados;
    private $tablaSolicitudes;
    private $tablaSolicitudesDetalle;


    public function __construct()
    {
        $this->tablaEmpleados = 'empleados';
        $this->tablaSolicitudes = 'solicitud_vacaciones';
        $this->tablaSolicitudesDetalle = 'solicitud_vacaciones_detalle';
    }



    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        //Antes de generar la vista de registro de vacaciones, debemos comprobar
        //Si el usuario en cuestion, no tiene una solicitud pendiente
        //Si la tiene, mandamos una bandera, para que el calendario no se dibuje y de esa
        //Forma el usuario no pueda generar otra solicitud
        /**
         * Tambien debemos comprobar si el usuario cuenta aun con días pendientes
         */



        $bandera = false;
        $mensaje = '';
        $idUser = Auth::user()->id;

        $empleado = DB::table($this->tablaEmpleados)
                 ->where('idUser', '=', $idUser)
                 ->get();

        //Generamos la fecha en la que el empleado ingreso a trabajar
        $fechaInicio = Carbon::createFromFormat('Y-m-d',$empleado[0]->fechaIngreso);

        //Generamos la fecha actual para poder crear la operacion de la diferencia de años
        $fechaFin = Carbon::createFromFormat('d/m/Y', Carbon::now()->format('d/m/Y'));

        //Obtenemos la diferencia de años que existe entre el año en el que el usuario ingreso y el año actual
        $diffYears = $fechaInicio->diffInYears($fechaFin);



        //Si la diferencia es menor o igual a 0 significa que el usuario no puede pedir vacaciones
        //Ya que aun no cumple el año de servicio que marca la ley
        if($diffYears <=0)
        {
            $bandera = true;
            $mensaje = 'Lo sentimos, actualmente no cumples con el requisito mínimo de un año de servicio para solicitar vacaciones. Por favor, ten en cuenta que debes completar al menos un año de servicio antes de poder generar una solicitud de vacaciones. Si tienes alguna pregunta o necesitas más información, por favor contacta a Recursos Humanos o a tu jefe inmediato.';
        }
        //Comprobamos si el usuario no tiene una solicitud pendiente en estado de revision
        else if (DB::table($this->tablaSolicitudes)->where('id_empleado', $empleado[0]->id)->where('estatus' , '=' , '1')->exists()) {
            $bandera = true;
            $mensaje = 'Tu solicitud de vacaciones se encuentra actualmente en proceso de revisión. Por favor, ten en cuenta que no podrás generar otra solicitud hasta que esta sea completada. Si tienes alguna pregunta o necesitas asistencia, por favor contacta a Recursos Humanos o a tu jefe inmediato.';
        //Debemos comprobar si el usuario aun no sobrepasa el limite de días que puede pedir de vacaciones
        }else if(!$this->validarDiasVacaciones($empleado[0]->id , $diffYears)){
            $bandera = true;
            $mensaje = 'Queremos informarte que has alcanzado el límite de días de vacaciones permitidos para este año. Por favor, ten en cuenta que no podrás solicitar más días de vacaciones hasta que se inicie el próximo año fiscal.';
        }


       return view('registroVacaciones' , ['bandera' => $bandera , 'mensaje' => $mensaje]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        /**
         *
         * Aqui debemos comprobar si el usuario aun tiene días
         *
         * Si la cantidad de días recibida, es mayor al numero de días que le quedan
         * al usuario por tomar vacaciones, debemos de envíar un mensaje de alerta
         */

        try {

            $idUser = Auth::user()->id;

            $empleado = DB::table($this->tablaEmpleados)
                 ->where('idUser', '=', $idUser)
                 ->get();

            $data = $request->all();

            $diasPedidos = count($data['data']);


            if($this->validarTotalDias($diasPedidos , $empleado[0]->id ,  $empleado[0]->fechaIngreso))
            {
                DB::transaction(function ()  use($empleado , $data , $diasPedidos)
                {
                    $id = DB::table($this->tablaSolicitudes)->insertGetId(
                        [
                            'id_empleado' => $empleado[0]->id,
                            'fecha' => Carbon::now()->format('Y-m-d H:i:s'),
                            'dias' => $diasPedidos,
                            'observaciones' => null,
                            'estatus' => '1',
                            'created_at' => Carbon::now(),
                        ]
                    );

                    foreach ($data['data'] as $key ) {
                        DB::table($this->tablaSolicitudesDetalle)->insert([
                            'id_solicitud' => $id,
                            'fecha' => $key,
                            'estatus' => true,
                            'created_at' => Carbon::now()
                        ]);
                    }

                });
            }else{
                return response('La cantidad de días de vacaciones que has solicitado sobrepasa el límite anual permitido. Por favor, verifica esta situación con el Departamento de Recursos Humanos para obtener más información.' , 422);
            }


        } catch (\Throwable $th) {
            return response($th , 500);
        }


        return response('Petición exitosa' , 200);



    }



    /**
     * Metodo para comprobar si un usuario en especifico aun se encuentra dentro del rango de días de vacaciones que
     * puede tomar de acuerdo al tiempo de antiguedad que lleva trabajando
     *
     *
     */

     private function validarDiasVacaciones($idEmpleado , $diffYear)
     {
        $anioActual = date('Y');

        $totalDias = DB::table('solicitud_vacaciones')
            ->where('id_empleado', '=', $idEmpleado)
            ->whereYear('fecha', '=', $anioActual)
            ->sum('dias');

            $resultado = DB::table('dias_vacaciones')
            ->select('dias')
            ->where('anios', '=', $diffYear)
            ->get();

        return ($totalDias < $resultado[0]->dias) ? true : false;
    }


    //Metodo para comprobar si el usuario no sobrePasa la cantidad de días permitidos que puede tomar de vacaciones
    private function validarTotalDias($dias , $idEmpleado, $fechaIngreso)
    {
        $anioActual = date('Y');

        //Obtenemos la cantidad de vacaciones que ha tomado el usuario en todo este año
        $totalDias = DB::table($this->tablaSolicitudes)
            ->where('id_empleado', '=', $idEmpleado)
            ->whereYear('fecha', '=', $anioActual)
            ->sum('dias');

        //Sumamos la cantidad de días que pidio el usuario, con el total de días que ya tiene en este año
        //para comprobar que aun se mantenga debajo del limite de días que puede tomar en el año de acuerdo
        // a los años de antiguedad que tiene
        $totalDias = $totalDias + $dias;

        //Generamos la fecha en la que el empleado ingreso a trabajar
        $fechaInicio = Carbon::createFromFormat('Y-m-d',$fechaIngreso);

        //Generamos la fecha actual para poder crear la operacion de la diferencia de años
        $fechaFin = Carbon::createFromFormat('d/m/Y', Carbon::now()->format('d/m/Y'));

        //Obtenemos la diferencia de años que existe entre el año en el que el usuario ingreso y el año actual
        $diffYears = $fechaInicio->diffInYears($fechaFin);


        $resultado = DB::table('dias_vacaciones')
            ->select('dias')
            ->where('anios', '=', $diffYears)
            ->get();

        return ($totalDias < $resultado[0]->dias) ? true : false;

    }
}
