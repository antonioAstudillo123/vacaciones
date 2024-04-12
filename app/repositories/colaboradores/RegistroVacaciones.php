<?php

namespace App\repositories\colaboradores;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;


class RegistroVacaciones{

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
     * Buscamos si un usuario tiene una solicitud en estatus de pendiente
     *
     * @param [type] $idEmpleado
     * @return void
     */
    public function validarSolicitudPendiente($idEmpleado)
    {
        return DB::table($this->tablaSolicitudes)->where('id_empleado', $idEmpleado)->where('estatus' , '=' , 'Pendiente')->exists();
    }


    /**
     * Debemos sumar la cantidad de días que ha tenido aprobados un cierto empleado. Este valor lo podemos usar
     * para validar si aun tiene días disponibles de vacaciones.
     */

    public function contarDiasAprobados($idEmpleado , $anioActual)
    {
       return DB::table($this->tablaSolicitudes)
            ->where('id_empleado', '=', $idEmpleado)
            ->whereYear('fecha', '=', $anioActual)
            ->where('estatus', '=' , 'Aprobada')
            ->sum('dias');
    }


    /**
     * Obtenemos los días de vacaciones que puede tener un empleado de acuerdo a los años de antiguedad que tiene
     * laborando en Univer
     *
     * Este metodo recibe un valor, que debe un año, de esa forma podemos obtener cuandos días de vacaciones puede tener
     * un empleado de acuerdo al año que lleva laborando
     */

     public function getDiasVacaciones($diffYear)
     {
        return DB::table('dias_vacaciones')
            ->select('dias')
            ->where('anios', '=', $diffYear)
            ->get();
     }


     /**
      * Creamos un nuevo registro de solicitud de vacaciones
      *
      * @param [id del empleado] $id
      * @param [La fecha en la que inicia el periodo de vacaciones que el empleado solicita] $fechaInicio
      * @param [los días que el empleado pidio] $diasPedidos
      * @return integer
      */
     public function store($id , $fechaInicio , $diasPedidos):int{
        $id = DB::table($this->tablaSolicitudes)->insertGetId(
            [
                'id_empleado' => $id,
                'fecha' => Carbon::now()->format('Y-m-d H:i:s'),
                'fechaInicio' =>  $fechaInicio,
                'dias' => $diasPedidos,
                'observaciones' => null,
                'estatus' => 'Pendiente',
                'created_at' => Carbon::now(),
            ]
        );

        return $id;
     }


     /**
      * Este metodo nos sirve para insertar un registro en la tabla solicitud_vacaciones_detalle
      *
      * @return void
      */
     public function createFechaDetalle($idSolicitud , $fecha)
     {
       return DB::table($this->tablaSolicitudesDetalle)->insert([
            'id_solicitud' => $idSolicitud,
            'fecha' => $fecha,
            'estatus' => true,
            'created_at' => Carbon::now()
        ]);
     }



     /**
      * Obtenemos la información del jefe de un colaborador, para de esa manera enviarle un correo
        recibimos el id del empleado

      */

      public function getDataJefe($idEmpleado)
      {
        return DB::table('empleados')
                        ->select('colaborador', 'correo')
                        ->where('id', function ($query) use($idEmpleado) {
                            $query->select('idJefe')
                                ->from('empleados')
                                ->where('id',  $idEmpleado);
                        })
                        ->first();
      }
}
