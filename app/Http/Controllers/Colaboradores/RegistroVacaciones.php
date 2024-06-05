<?php

namespace App\Http\Controllers\Colaboradores;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\colaboradores\RegistroVacaciones as RegistroVacacionesService;

class RegistroVacaciones extends Controller
{
    private $servicio;


    public function __construct( RegistroVacacionesService $servicio)
    {
        $this->servicio = $servicio;
    }


    /**
     * Antes de generar la vista de registro de vacaciones, debemos comprobar
     * Si el empleado en cuestion, no tiene una solicitud pendiente
     * Si la tiene, mandamos una bandera, para que el calendario no se dibuje y de esa
     * Forma el empleado no pueda generar otra solicitud
     * Tambien debemos comprobar si el empleado cuenta aun con días pendientes o si el empleado
     * aun no cumple el año de servicio
     */


    public function index()
    {
        $bandera = false;
        $mensaje = '';

        if(!$this->servicio->validarAntiguedad())
        {
            $bandera = true;
            $mensaje = 'Lo sentimos, actualmente no cumples con el requisito mínimo de un año de servicio para solicitar vacaciones. Por favor, ten en cuenta que debes completar al menos un año de servicio antes de poder generar una solicitud de vacaciones. Si tienes alguna pregunta o necesitas más información, por favor contacta a Recursos Humanos o a tu jefe inmediato.';
        }
        else if($this->servicio->validarSolicitudPendiente()){
            $bandera = true;
            $mensaje = 'Tu solicitud de vacaciones se encuentra actualmente en proceso de revisión. Por favor, ten en cuenta que no podrás generar otra solicitud hasta que esta sea completada. Si tienes alguna pregunta o necesitas asistencia, por favor contacta a Recursos Humanos o a tu jefe inmediato.';
        }
        else if(!$this->servicio->validarDiasVacaciones()){
            $bandera = true;
            $mensaje = 'Queremos informarte que has alcanzado el límite de días de vacaciones permitidos para este año. Por favor, ten en cuenta que no podrás solicitar más días de vacaciones hasta que se inicie el próximo año fiscal.';
        }


       return view('dashboard.colaboradores.submodulos.registroVacaciones' , ['bandera' => $bandera , 'mensaje' => $mensaje]);
    }


    /**
     * Metodo usado para almacenar la solicitud del empleado
     *
     *
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        $data = $request->all();
        return $this->servicio->store($data);
    }

}
