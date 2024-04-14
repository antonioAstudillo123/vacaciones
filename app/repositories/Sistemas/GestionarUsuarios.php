<?php

namespace App\repositories\Sistemas;
use Illuminate\Support\Facades\DB;
use App\Traits\Paginador as PaginadorTrait;

class GestionarUsuarios{

    Use PaginadorTrait;

    /**
     * Obtenemos la informacion del empleado, para llenar select en modulo de gestionar usuarios
     *
     */
    public function getEmpleadoSinUser()
    {
        return DB::table('empleados')->select('numeroEmpleado' , 'colaborador')->where('idUser' , '=' , null)->get();
    }



    /**
     * Obtenemos la información de los planteles para llenar select en modulo gestionar usuarios
     */

    public function getPlanteles()
    {
        return DB::table('planteles')->select('id' , 'nombre')->get();
    }


    /**
     * Obtenemos los usuarios filtrados
     */

    public function getUsersFiltros($data)
    {
        $query = DB::table('users as u')
        ->select('u.id', 'u.name', 'u.email', 'p.nombre as puesto', 'plan.nombre as plantel', 'a.nombre as nombreArea' ,'p.id as idPuesto' , 'plan.id as idPlantel' , 'a.id as idArea')
        ->join('empleados as e', 'e.idUser', '=', 'u.id')
        ->join('puestos as p', 'p.id', '=', 'e.idPuesto')
        ->join('planteles as plan', 'plan.id', '=', 'e.idPlantel')
        ->join('areas as a', 'a.id', '=', 'e.idArea')
            ->where(function ($query) use ($data) {
                $query->where('u.name', 'LIKE', '%' . $data['value'] . '%')
                    ->orWhere('u.email', 'LIKE', '%' . $data['value'] . '%')
                    ->orWhere('p.nombre', 'LIKE', '%' . $data['value'] . '%');
            });

        $this->inicializarAtributos($data , $query);
        $this->paginarBusqueda();

        return $this->respuesta();
    }

    /**
     * Obtenemos todos los usuarios
     */

     public function getUsers($data)
     {
        $query = DB::table('users as u')
        ->select('u.id', 'u.name', 'u.email', 'p.nombre as puesto', 'plan.nombre as plantel', 'a.nombre as nombreArea' ,'p.id as idPuesto' , 'plan.id as idPlantel' , 'a.id as idArea')
        ->join('empleados as e', 'e.idUser', '=', 'u.id')
        ->join('puestos as p', 'p.id', '=', 'e.idPuesto')
        ->join('planteles as plan', 'plan.id', '=', 'e.idPlantel')
        ->join('areas as a', 'a.id', '=', 'e.idArea');


        $this->inicializarAtributos($data , $query);
        $this->paginarTotal();

        return $this->respuesta();
     }
}
