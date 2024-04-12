<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait Auxiliares{

    /**
     * Metodo para obtener que empleado le corresponde a un User
     */

     public function getEmpleado($idUser)
     {
        return DB::table($this->tablaEmpleados)
        ->where('idUser', '=', $idUser)
        ->get();
     }
}
