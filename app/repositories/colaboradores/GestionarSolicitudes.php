<?php

namespace App\repositories\colaboradores;
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

    /**
     * Este metodo se llama desde un servicio y se ejecuta cuando el valor del input de search cuenta con informacion.
     *
     * La logica es consultar todas las solicitudes de los subordinados de un jefe inmediato por ese motivo
     * es importante que se envie el id del jefe para que el filtro se pueda realizar correctamente
     *
     * @param [string] $valor Es el valor o dato que usaremos como filtro
     * @param [type] $idJefe Es el id del jefe
     * @return response->json
     */
    public function getSolicitudesFiltradas($data , $idJefe)
    {

        $query = DB::table('solicitud_vacaciones as sv')
                ->select('sv.id', DB::raw("DATE_FORMAT(sv.fecha, '%d-%m-%Y  -- %H:%i:%s %p') AS fecha"), 'sv.dias', 'sv.estatus', 'e.colaborador')
                ->join('empleados as e', 'e.id', '=', 'sv.id_empleado')
                ->where('e.idJefe', $idJefe)
                ->where(function ($query) use ($data) {
                        $query->where('sv.fecha', 'LIKE', '%' . $data['value'] . '%')
                            ->orWhere('e.numeroEmpleado', 'LIKE', '%' . $data['value'] . '%')
                            ->orWhere('sv.dias', 'LIKE', '%' . $data['value'] . '%')
                            ->orWhere('e.colaborador', 'LIKE', '%' . $data['value'] . '%')
                            ->orWhere('sv.estatus', 'LIKE', '%' . $data['value'] . '%')
                            ->orWhere('e.fechaIngreso', 'LIKE', '%' . $data['value'] . '%');
                        });

        $this->inicializarAtributos($data , $query);
        $this->paginarBusqueda();

        return $this->respuesta();

    }


    /**
     * Obtener todos los registros de solicitudes que han realizado los subordinados de un jefe inmediato
     *
     * @param [string] $idJefe  Es el id del jefe
     * @return response->json
     */
    public function getSolicitudesAll($data , $idJefe)
    {
        $query = DB::table('solicitud_vacaciones as sv')
        ->select('sv.id', DB::raw("DATE_FORMAT(sv.fecha, '%d-%m-%Y -- %H:%i:%s %p') AS fecha"), 'sv.dias', 'sv.estatus', 'e.colaborador')
        ->join('empleados as e', 'e.id', '=', 'sv.id_empleado')
        ->where('e.idJefe', $idJefe);

        $this->inicializarAtributos($data , $query);
        $this->paginarTotal();

        return $this->respuesta();
    }


    /**
     * Con este metodo vamos a obtener el detallado de fechas que solicito un determinado empleado
     */

     public function getDetailVacation($idSolicitud)
     {
         try {
             $resultado = DB::table($this->tablaSolicitudesDetalle)
             ->select('id' , DB::raw("DATE_FORMAT(fecha, '%d-%m-%Y') AS fecha") , DB::raw("DATE_FORMAT(fecha, '%Y-%m-%d %H:%i:%s %p') AS dia"))
             ->orderBy('fecha')
             ->where('id_solicitud' , '=' , $idSolicitud)
             ->get();
         } catch (Exception $th) {
             return response('Tuvimos problemas al procesar la solicitud'  , 500);
         }

         return response()->json(['data' => $resultado]);

     }


     /**
      * Con este metodo vamos a setear el estatus de una solicitud
      *
      * @param [string] $estatus este valor puede ser: Aprobada, Rechazada, Pendiente
      * @param [string] $idSolicitud  el id de la solicitud
      * @return void
      */
     public function setEstatusSolicitud($estatus , $idSolicitud , $observaciones)
     {
        return DB::table($this->tablaSolicitudes)
        ->where('id', $idSolicitud)
        ->update(
            [
                'estatus' => $estatus,
                'observaciones' => $observaciones
            ]
        );
     }


     /**
      * Usamos este metodo para obtener el correo y el nombre de un colaborador y de esa forma poder ir preparando
        el correo que se le envíara indicandole que su solicitud fue aprobada
    */


    public function getDataEmpleado($idSolicitud)
    {
        return DB::table($this->tablaEmpleados)
            ->join('solicitud_vacaciones as sv', function ($join) use ($idSolicitud){
                $join->on('sv.id_empleado', '=', 'empleados.id')
                    ->where('sv.id', '=', $idSolicitud);
            })
            ->select('empleados.correo' , 'empleados.colaborador')
            ->first();
    }

    /**
     * Obtenemos las fechas de una solicitud
     */

    public function getFechas($idSolicitud)
    {
        return DB::table('solicitud_vacaciones_detalle')
               ->select('fecha')
               ->where('id_solicitud', '=', $idSolicitud )
               ->get();
    }


}
