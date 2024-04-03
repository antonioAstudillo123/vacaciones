<?php

namespace App\Http\Controllers\Sistemas;

use Exception;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
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

        $planteles = DB::table('planteles')->select('id' , 'nombre')->get();
        $roles = DB::table('roles')->select('id' , 'name')->get();

        return view('dashboard.sistemas.submodulos.gestionarUsuarios' , ['colaboradores' => $colaboradores , 'planteles' => $planteles , 'roles' => $roles]);
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
        ->select('id','colaborador' , 'correo' , 'numeroEmpleado')
        ->get();

        DB::transaction(function () use($resultados)
        {
            foreach ($resultados as $item)
            {

                if($item->correo !== '')
                {
                    $id = DB::table('users')->insertGetId(
                        [
                            'name' => $item->colaborador,
                            'password' => Hash::make('Univer#0'),
                            'email' =>  $item->correo,
                        ]
                    );

                    DB::table('empleados')
                    ->where('id', $item->id)
                    ->update(['idUser' => $id]);
                }

            }

    });


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

        DB::transaction(function () use ($data)
        {
            try
            {
                $id = DB::table('users')->insertGetId(
                    [
                        'name' => Str::title($data['user']),
                        'email' => $data['email'] ,
                        'password' => Hash::make($data['password']),
                        'created_at' => Carbon::now()
                    ]
                );

                DB::table('empleados')
                    ->where('numeroEmpleado', '=' , $data['colabordor'])
                    ->update(['idUser' => $id]);

                $user = User::find($id);
                $role = Role::findByName($data['role']);
                $user->assignRole($role);

            } catch (QueryException  $th)
            {
                throw $th;
            }
        });


        return response('Usuario agregado correctamente' , 200);
     }



     public function updatePassword(Request $request)
     {

        try {
                $id = Auth::user()->id;

                DB::table('users')
                ->where('id' , $id)
                ->update(
                    [
                        'password' => Hash::make($request->input('password'))
                    ]
                );
        } catch (\Throwable $th) {
           return response('Tuvimos problemas para actualizar la contraseña' , 500);
        }

        return response('Contraseñas actualizadas correctamente!' , 200);
     }


     public function changePermisos(Request $request)
     {
        try
        {
            $data = $request->all();
            $user = User::where('email', $data['email'])->first();
            $user->syncRoles($data['role']);
        } catch (\Throwable $th) {
            return response('No pudimos actualizar el role' , 500);
        }

        return response('Perfil actualizado correctamente' , 200);

     }


     //Metodo para  visualizar la vista de resetPassword
     function resetPassword()
     {
       return view('resetPassword');
     }

}
