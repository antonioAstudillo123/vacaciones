<?php

namespace App\Http\Controllers\Sistemas;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Traits\Paginador as PaginadorTrait;

class GestionarUsuarios extends Controller
{
    Use PaginadorTrait;

    public function index()
    {
        return view('dashboard.sistemas.submodulos.gestionarUsuarios');
    }


    public function all(Request $request)
    {
        $busqueda = $request->input('search.value');

        if(!empty($busqueda))
        {
            $query = DB::table('users as u')
                ->select('u.id', 'u.name', 'u.email', 'p.nombre as puesto')
                ->join('empleados as e', 'e.idUser', '=', 'u.id')
                ->join('puestos as p', 'p.id', '=', 'e.idPuesto')
                ->where(function ($query) use ($busqueda) {
                    $query->where('u.name', 'LIKE', '%' . $busqueda . '%')
                        ->orWhere('u.email', 'LIKE', '%' . $busqueda . '%')
                        ->orWhere('p.nombre', 'LIKE', '%' . $busqueda . '%');
                });

                $this->inicializarAtributos($request , $query);
                $this->paginarBusqueda();
        }else{


            $query = DB::table('users as u')
                ->select('u.id', 'u.name', 'u.email', 'p.nombre as puesto')
                ->join('empleados as e', 'e.idUser', '=', 'u.id')
                ->join('puestos as p', 'p.id', '=', 'e.idPuesto');

            $this->inicializarAtributos($request , $query);
            $this->paginarTotal();
        }

        return $this->respuesta();
    }




    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //Creamos a los usuarios


        $resultados = DB::table('empleados')
        ->select('id','colaborador' , 'numeroEmpleado')
        ->get();


        foreach ($resultados as $item)
        {

            DB::transaction(function () use($item)
            {
                $id = DB::table('users')->insertGetId(
                    [
                        'name' => $item->colaborador,
                        'password' => Hash::make('Univer#0'),
                        'email' =>  $item->numeroEmpleado . '@gmail.com',
                    ]
                );

                DB::table('empleados')
                ->where('id', $item->id)
                ->update(['idUser' => $id]);
            });

        }


        return 'Proceso finalizado';


    }



}
