<?php


/**
 * La finalidad de este TRAIT es poder reutilizar el codigo del paginador, en todos los controladores que lo requieran.
 */

namespace App\Traits;

use Illuminate\Http\Request;


trait Paginador{
    private $busqueda;
    private $start;
    private $registrosPorPagina;
    private $paginaActual;
    private $registrosOmitidos;
    private $totalRegistrosEnTabla;
    private $data;
    private $query;
    private $draw;
    private $order;

    /**
     * Inicializamos todos los atributos del paginador
     */
    public function inicializarAtributos(array $request , $query)
    {
        //Obtenemos el valor de la busqueda en caso de que exista
        $this->busqueda =  $request['value'];

        // Obtén el valor 'start' de la solicitud AJAX
        $this->start = $request['start'];

        // Obtén el número de registros por página
        $this->registrosPorPagina = $request['length'];

        // Calcula la página actual
        $this->paginaActual = floor($this->start / $this->registrosPorPagina) + 1;

        //Calcula el número de registros a omitir (skip) para llegar a la página actual
        $this->registrosOmitidos = ($this->paginaActual - 1) * $this->registrosPorPagina;

        //Utilizamos esta valor para evitar ataques CSRF, me lo manda datatable es un número entero que se incrementa
        //con cada solicitud de datatable, y debe ser reflejado en la respuesta del servidor sin cambios. Su principal funcion
       // es ayudar a Datatable a reconocer si la respuesta del servidor corresponde a la solicitud original o a una solicitud anterior
        $this->draw = $request['draw'];

        //Inicializamos la query que nos envian desde el controlador
        $this->query = $query;

        //Contabilizamos la cantidad de registros que tiene la query
        $this->totalRegistrosEnTabla = $this->query->count();
    }


    /**
     * Con este metodo lo que hacemos es obtener todos los registros de la query
     */
    public function paginarTotal()
    {
        $this->query = $this->query->skip($this->registrosOmitidos)->take($this->registrosPorPagina);
        $this->data = $this->query->get();
    }


    /**
     * Obtenemos los registros que trae la query de manera filtrada de acuerdo al valor que el usuario ingresa en el input seach
     */
    public function paginarBusqueda($query = null)
    {
        $this->query = $this->query->skip($this->registrosOmitidos)->take($this->registrosPorPagina);

        $this->data = $this->query->get();
    }



    //Retornamos la respuesta de las query ejecutadas en el servidor, para que Datatable las pueda procesar en la vista.
    public function respuesta()
    {
        return response()->json([
                'data' => $this->data,
                'draw' => $this->draw,
                'recordsTotal' => $this->totalRegistrosEnTabla,
                'recordsFiltered' =>$this->totalRegistrosEnTabla ,
            ]);
    }

}
