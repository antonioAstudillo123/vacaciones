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

        //Creamos roles
        // Role::create(['name' => 'administrador']);
        // Role::create(['name' => 'humanos']);
        // Role::create(['name' => 'colaborador']);


        //asignamos permisos a un rol
        $role = Role::findByName('colaborador');


         //Asignamos permisos a role
        $permissions = Permission::whereIn('name',
        [
            'solicitar vacaciones' ,
            // 'gestionar solicitudes',
            // 'solicitudes',
            // 'gestionar colaboradores',
            // 'recursos humanos',
            // 'sistemas'
        ])->get();

        $role->syncPermissions($permissions);

        //Asignar un rol al usuario
        // $user = User::find(79);

        // $role = Role::findByName('colaborador');
        // $user->assignRole($role);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
