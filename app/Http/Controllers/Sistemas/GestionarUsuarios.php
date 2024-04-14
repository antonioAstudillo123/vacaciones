<?php

namespace App\Http\Controllers\Sistemas;

use Exception;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;
use App\Traits\Paginador as PaginadorTrait;
use App\Services\Sistemas\GestionarUsuarios as GestionarUsuariosService;


class GestionarUsuarios extends Controller
{
    Use PaginadorTrait;
    private $servicio;

    public function __construct(GestionarUsuariosService $servicio)
    {
        $this->servicio = $servicio;
    }

    public function index()
    {
        /**
         * Debemos de enviar la data con la que vamos a llenar los select's
         */

        $colaboradores = $this->servicio->getEmpleadoSinUser();
        $planteles = $this->servicio->getPlanteles();
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
        $pagination = array(
            'value' => $request->input('search.value'),
            'start' => $request->input('start'),
            'length' => $request->input('length'),
            'draw' =>  $request->input('draw')
        );

        if(!empty($pagination['value']))
        {
            return $this->servicio->getUsersFiltros($pagination);
        }else{
            return $this->servicio->getUsers($pagination);
        }

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
            Log::error('Error en método destroy ERROR 004 ' . $th->getMessage());
            return response('Tuvimos problemas para eliminar al usuario Error:004 ' , 200);
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

                // Le asignamos el role al usuario
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
