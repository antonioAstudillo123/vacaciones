<?php

namespace App\Services\colaboradores;


use Exception;
use App\Traits\Auxiliares;
use App\Mail\CorreoSolicitud;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\repositories\colaboradores\RegistroVacaciones as RegistroVacacionesRepository;

class RegistroVacaciones
{
    Use Auxiliares;

    private $tablaEmpleados;
    private $tablaSolicitudes;
    private $tablaSolicitudesDetalle;
    private $repositorio;

    public function __construct( RegistroVacacionesRepository $repository)
    {
        $this->tablaEmpleados = 'empleados';
        $this->tablaSolicitudes = 'solicitud_vacaciones';
        $this->tablaSolicitudesDetalle = 'solicitud_vacaciones_detalle';
        $this->repositorio = $repository;
    }


    /**
     * Este metodo nos va ayudar a validar si el usuario en cuestion, ya cumple el año de servicio, por lo cual
     * ya puede pedir vacaciones.
     *
     * Retornamos false en caso de que no cumpla, true en caso de que si cumpla el año de servicio
     *
     * @return boolean
     */
    public function validarAntiguedad(): bool
    {
        //Obtenemos el ID del usuario autenticado
        $idUser = Auth::user()->id;


        //Obtenemos el empleado asociado al user que esta autenticado usando un metodo generico del trait Auxiliares
        $empleado = $this->getEmpleado($idUser);

        //Generamos la fecha en la que el empleado ingreso a trabajar
        $fechaInicio = $this->setFechaInicio($empleado[0]->fechaIngreso);

        //Generamos la fecha actual para poder crear la operacion de la diferencia de años
        $fechaFin = $this->fechaActual();

        //Obtenemos la diferencia de años que existe entre el año en el que el usuario ingreso y el año actual
        $diffYears = $fechaInicio->diffInYears($fechaFin);

        return ($diffYears <=0) ? false : true;

    }

    /**
     * Este metodo nos sirve para validar si el usuario autenticado no tiene una solicitud que se encuentre en proceso de
     * aprobar o rechazar, si la tiene no puede generar una nueva.
     *
     * Retorna true en caso de que  tenga solicitudes pendientes de aprobar, false en caso de que tenga no una solicitud pendiente
     * de aprobar
     *
     * @return boolean
     */
    public function validarSolicitudPendiente(): bool
    {

        //Obtenemos el ID del usuario autenticado
        $idUser = Auth::user()->id;

        //Obtenemos el empleado asociado al user que esta autenticado usando un metodo generico del trait Auxiliares
        $empleado = $this->getEmpleado($idUser);



        return $this->repositorio->validarSolicitudPendiente($empleado[0]->id);
    }

    /**
         * Metodo para comprobar si un usuario en especifico aun se encuentra dentro del rango de días de vacaciones que
        * puede tomar de acuerdo al tiempo de antiguedad que lleva trabajando
    *
     *
     */

     public function validarDiasVacaciones()
     {
        $anioActual = date('Y');

        //Obtenemos el ID del usuario autenticado
        $idUser = Auth::user()->id;

        //Obtenemos el empleado asociado al user que esta autenticado usando un metodo generico del trait Auxiliares
        $empleado = $this->getEmpleado($idUser);

        //Generamos la fecha actual para poder crear la operacion de la diferencia de años
        $fechaFin = $this->fechaActual();

        //Generamos la fecha en la que el empleado ingreso a trabajar
        $fechaInicio = $this->setFechaInicio($empleado[0]->fechaIngreso);

        //Obtenemos la diferencia de años que existe entre el año en el que el usuario ingreso y el año actual
        $diffYears = $fechaInicio->diffInYears($fechaFin);

        $totalDias = $this->repositorio->contarDiasAprobados($empleado[0]->id , $anioActual );

        $resultado = $this->repositorio->getDiasVacaciones($diffYears);

        return ($totalDias < $resultado[0]->dias) ? true : false;
    }




     /**
         *
         * Antes de hacer el insert de la solicitud con el detalle de días, debemos comprobar si el número de días
         * que el empleado está solicitando, se encuentra de bajo de los días que le quedan disponibles.
         *
         *
         * Si la cantidad de días recibida, es mayor al numero de días que le quedan
         * al usuario por tomar vacaciones, debemos de envíar un mensaje de alerta
    */
    public function store(array $data)
    {

        try
        {
            $idUser = Auth::user()->id;
            $empleado = $this->getEmpleado($idUser);
            $diasPedidos = count($data['data']);


            if($this->validarTotalDias($diasPedidos , $empleado[0]->id ,  $empleado[0]->fechaIngreso))
            {

                DB::beginTransaction();

                try
                {

                    $id = $this->repositorio->store($empleado[0]->id , $data['data'][0] , $diasPedidos);

                    foreach ($data['data'] as $key )
                    {
                        $this->repositorio->createFechaDetalle($id , $key);
                    }


                    $resultado = $this->repositorio->getDataJefe($empleado[0]->id);
                    $nombreEmpleado = Auth::user()->name;
                    $nombreJefe = $resultado->colaborador;

                    //Mail::to('antonioastudillo206@gmail.com')->queue(new CorreoSolicitud( $nombreJefe , $nombreEmpleado , $data['data']));

                    DB::commit();
                }
                catch (Exception $th)
                {
                    DB::rollback();
                    Log::error('Error 003: ' . $th->getMessage());
                    return response('No pudimos procesar la solicitud Error: 003 ' , 500);
                }

            }
            else
            {
                return response('La cantidad de días de vacaciones que has solicitado sobrepasa el límite anual permitido. Por favor, verifica esta situación con el Departamento de Recursos Humanos para obtener más información.' , 422);
            }


        }
        catch (Exception $th)
        {
            Log::error('Error 003: ' . $th->getMessage());
            return response('Tuvimos problemas al procesar tu solicitud Error : 003' , 500);
        }

        return response('Petición exitosa' , 200);

    }


      //Metodo para comprobar si el usuario no sobrePasa la cantidad de días permitidos que puede tomar de vacaciones
      private function validarTotalDias($dias , $idEmpleado, $fechaIngreso)
      {
        $anioActual = date('Y');

        //Obtenemos la cantidad de vacaciones que ha tomado el usuario en todo este año
        $totalDias =  $this->repositorio->contarDiasAprobados($idEmpleado , $anioActual);

        //Sumamos la cantidad de días que pidio el usuario, con el total de días que ya tiene en este año
        //para comprobar que aun se mantenga debajo del limite de días que puede tomar en el año de acuerdo
        // a los años de antiguedad que tiene
        $totalDias = $totalDias + $dias;

        //Generamos la fecha en la que el empleado ingreso a trabajar
        $fechaInicio = $this->setFechaInicio($fechaIngreso);

        //Generamos la fecha actual para poder crear la operacion de la diferencia de años
        $fechaFin =  $this->fechaActual();

        //Obtenemos la diferencia de años que existe entre el año en el que el usuario ingreso y el año actual
        $diffYears = $fechaInicio->diffInYears($fechaFin);
        $resultado = $this->repositorio->getDiasVacaciones($diffYears);

        return ($totalDias <= $resultado[0]->dias) ? true : false;

      }


      //Este metodo me va servir para setear la fecha de inicio
      //Debemos mandar la fecha en la que el empleado ingreso a trabajar
      private function setFechaInicio($fechaIngreso)
      {
        return Carbon::createFromFormat('Y-m-d',$fechaIngreso);
      }

      //Seteamos la fecha actual
      private function fechaActual()
      {
        return Carbon::createFromFormat('d/m/Y', Carbon::now()->format('d/m/Y'));
      }




}
