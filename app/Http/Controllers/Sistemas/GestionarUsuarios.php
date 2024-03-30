<?php

namespace App\Http\Controllers\Sistemas;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;
use App\Traits\Paginador as PaginadorTrait;

class GestionarUsuarios extends Controller
{
    Use PaginadorTrait;

    public function index()
    {
        /**
         * Debemos de enviar la data con la que vamos a llenar los select's
         */

        $colaboradores = DB::table('empleados')->select('numeroEmpleado' , 'colaborador')->where('idUser' , '=' , null)->get();


        return view('dashboard.sistemas.submodulos.gestionarUsuarios' , ['colaboradores' => $colaboradores]);
    }


    /**
     * Obtenemos la informacion de todos los usuarios para mostrarla en el datatable
     *
     * @param Request $request
     * @return void
     */
    public function all(Request $request)
    {
        $busqueda = $request->input('search.value');

        if(!empty($busqueda))
        {


            $query = DB::table('users as u')
            ->select('u.id', 'u.name', 'u.email', 'p.nombre as puesto', 'plan.nombre as plantel', 'a.nombre as nombreArea' ,'p.id as idPuesto' , 'plan.id as idPlantel' , 'a.id as idArea')
            ->join('empleados as e', 'e.idUser', '=', 'u.id')
            ->join('puestos as p', 'p.id', '=', 'e.idPuesto')
            ->join('planteles as plan', 'plan.id', '=', 'e.idPlantel')
            ->join('areas as a', 'a.id', '=', 'e.idArea')
                ->where(function ($query) use ($busqueda) {
                    $query->where('u.name', 'LIKE', '%' . $busqueda . '%')
                        ->orWhere('u.email', 'LIKE', '%' . $busqueda . '%')
                        ->orWhere('p.nombre', 'LIKE', '%' . $busqueda . '%');
                });

                $this->inicializarAtributos($request , $query);
                $this->paginarBusqueda();
        }else{


            $query = DB::table('users as u')
            ->select('u.id', 'u.name', 'u.email', 'p.nombre as puesto', 'plan.nombre as plantel', 'a.nombre as nombreArea' ,'p.id as idPuesto' , 'plan.id as idPlantel' , 'a.id as idArea')
            ->join('empleados as e', 'e.idUser', '=', 'u.id')
            ->join('puestos as p', 'p.id', '=', 'e.idPuesto')
            ->join('planteles as plan', 'plan.id', '=', 'e.idPlantel')
            ->join('areas as a', 'a.id', '=', 'e.idArea');

            $this->inicializarAtributos($request , $query);
            $this->paginarTotal();
        }

        return $this->respuesta();
    }




    /**
     * Con este metodo creamos todos los usuarios
     *
     * Obtenemos los empleados, y los vamos insertando en la tabla users
     *
     * actualizamos el idUser en la tabla empleados - colaboradores
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

    /**
     * Actualizamos la información de un usuario
     *
     * @param Request $request
     * @return void
     */
    public function update(Request $request)
    {
        $data = $request->all();
        $aux = [];

        if(isset($data['password']) && $data['password'] !== null )
        {
            $aux['password'] = Hash::make($data['password']);
        }

        DB::transaction(function () use ($data , $aux)
        {
            try
            {
                DB::table('users')
                ->where('id', $data['id'])
                ->update(
                    $aux +
                    [
                        'name' => $data['name'],
                        'email' => $data['email']
                    ],
                );
            } catch (QueryException  $e) {
                throw $e;

            }
            catch(Exception $e)
            {
                throw $e;
            }
        });

        return response('Usuario actualizado correctamente' , 200);
    }


    //Eliminamos un usuario del sistema
    public function destroy(Request $request)
    {
        try{


            // Desactivar la verificación de claves foráneas
            DB::statement('SET FOREIGN_KEY_CHECKS=0');

            DB::table('users')->where('id', '=', $request->input('id'))->delete();

            // Volver a activar la verificación de claves foráneas
            DB::statement('SET FOREIGN_KEY_CHECKS=1');

        }catch(Exception $e){
           // return response('Tuvimos problemas para eliminar al usuario' , 500);
            return response($e , 500);
        }

        return response('Usuario eliminado con éxito' , 200);
    }




    /**
     * Creamos un nuevo usuario en el sistema
     */

     public function create(Request $request)
     {
        $data = $request->all();

        try {

            DB::table('users')->insert(
                [
                    'name' => Str::title($data['user']),
                    'email' => $data['email'] ,
                    'password' => Hash::make($data['password']),
                    'created_at' => Carbon::now()
                ]
            );

        } catch (QueryException  $th) {

            if ($th->errorInfo[1] == 1062) {
                return response('El correo ingresado ya existe!' , 500);
            }else{
                return response('Tuvimos problemas al procesar la solicitud!' , 500);
            }

        }

        return response('Usuario agregado correctamente' , 200);
     }



     public function actualizarPassword()
     {
        DB::table('users')
        ->update(
            [
                'password' => Hash::make('Univer10')
            ]
        );
     }

}