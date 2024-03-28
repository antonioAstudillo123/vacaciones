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
        $role = Role::findByName('colaborador');

        // Obtener los permisos que deseas asignar al rol
        $permissions = Permission::whereIn('name', ['solicitar vacaciones'])->get();

        // Asignar permisos al rol
        $role->syncPermissions($permissions);
        // $user = User::find(1);

        // $role = Role::findByName('administrador');
        // $user->assignRole($role);
        // echo 'Asigno role a carlos';

        // $role = Role::findByName('colaborador');

        // $permissions = Permission::whereIn('name', ['solicitar vacaciones'])->get();

        // $role->syncPermissions($permissions);


        // $user = User::find(4);
        // // Asignar un rol al usuario
        // $role = Role::findByName('humanos');
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
