<?php

namespace App\Http\Controllers\Sistemas;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;

class Permisos extends Controller
{

    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //Creamos permisos
        // Permission::create(['name' => 'solicitar vacaciones']);
        // Permission::create(['name' => 'gestionar solicitudes']);
        // Permission::create(['name' => 'solicitudes']);
        // Permission::create(['name' => 'gestionar colaboradores']);
        // Permission::create(['name' => 'recursos humanos']);
        // Permission::create(['name' => 'sistemas']);

        // //Creamos roles
        // Role::create(['name' => 'administrador']);
        // Role::create(['name' => 'humanos']);
        // Role::create(['name' => 'colaborador']);
        Role::create(['name' => 'jefe']);


        //asignamos permisos a un rol
        $role = Role::findByName('jefe');


        // // Asignamos permisos a role
        $permissions = Permission::whereIn('name',
        [
            'solicitar vacaciones' ,
            'gestionar solicitudes',
            // 'solicitudes',
            // 'gestionar colaboradores',
            // 'recursos humanos',
            // 'sistemas'
        ])->get();

        $role->syncPermissions($permissions);

        //Asignar un rol al usuario
        // $user = User::find(1);

        // $role = Role::findByName('administrador');
        // $user->assignRole($role);
    }


}
