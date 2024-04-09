<?php

namespace app\repositories\correos;

use Illuminate\Support\Facades\DB;


class SolicitudRepository {

    private $tabla;

    public function __construct()
    {
        $this->tabla = 'solicitud_vacaciones';
    }


    /**
     * En este metodo vamos a leer todas las solicitudes de acuerdo a un tipo de estatus
     *
     *
     */
    public function leerSolicitudes($estatus)
    {
        return DB::table($this->tabla)
        ->where('estatus' , '=' , $estatus)
        ->get();
    }




}
