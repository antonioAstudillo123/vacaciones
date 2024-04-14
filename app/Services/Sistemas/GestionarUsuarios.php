<?php

namespace App\Services\Sistemas;
use App\repositories\Sistemas\GestionarUsuarios as GestionarUsuariosRepository;

class GestionarUsuarios{

    private $repositorio;

    public function __construct(GestionarUsuariosRepository $repositorio)
    {
        $this->repositorio = $repositorio;
    }


    /**
     * Obtenemos todos los empleados que no tengan un usuario asignado
     *
     * @return void
     */
    public function getEmpleadoSinUser()
    {
        return $this->repositorio->getEmpleadoSinUser();
    }


    /**
     * Obtenemos los planteles
     */

     public function getPlanteles()
     {
        return $this->repositorio->getPlanteles();
     }


     /**
      * Obtenemos los usuarios filtrados
      */

    public function getUsersFiltros($data)
    {
        return $this->repositorio->getUsersFiltros($data);
    }

    /**
     * Obtenemos todos los usuarios
     */

     public function getUsers($data)
     {
        return $this->repositorio->getUsers($data);
     }

}
