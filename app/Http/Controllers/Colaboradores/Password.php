<?php

namespace App\Http\Controllers\Colaboradores;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class Password extends Controller
{
    public function index()
    {
        return view('resetPassword');
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

}
