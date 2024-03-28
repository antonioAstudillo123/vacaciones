<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Sistemas\Permisos;
use App\Http\Controllers\RH\ConsultarSolicitudes;
use App\Http\Controllers\Sistemas\GestionarUsuarios;
use App\Http\Controllers\Colaboradores\RegistroVacaciones;
use App\Http\Controllers\Colaboradores\GestionarSolicitudes;

Route::redirect('/', '/login');

Route::middleware(['auth'])->group(function()
{
    Route::group(['middleware' => ['can:sistemas,gestionar colaboradores']  ], function()
    {
        Route::controller(GestionarUsuarios::class)->group(function(){
            Route::get('/sistemas/gestionarUsuarios' , 'index')->name('gestionarUsuarios.index');
            Route::get('/sistemas/crearUsuario' , 'store');
            Route::get('/sistemas/all' , 'all');
            Route::post('/sistemas/update' , 'update');
            Route::post('/sistemas/delete' , 'destroy');
            Route::post('/sistemas/create' , 'create');
        });

    });


    Route::group(['middleware' => ['can:solicitar vacaciones']] , function(){
        Route::controller(RegistroVacaciones::class)->group(function()
        {
            Route::get('/colaboradores/registroVacaciones', 'index')->name('registroVacaciones.index');
            Route::post('/colaboradores/registroVacaciones' , 'store');
        });
    });


    Route::group(['middleware' => ['can:gestionar solicitudes']] , function(){
        Route::controller(GestionarSolicitudes::class)->group(function(){
            Route::get('/colaboradores/gestionarSolicitudes' , 'index')->name('gestionarSolicitudes.index');
            Route::get('/colaboradores/getSolicitudes' , 'getSolicitudes');
            Route::post('/colaboradores/getSolicitudUser' , 'getSolicitudUser');
            Route::post('/colaboradores/aprobarSolicitud' , 'aprobarSolicitud');
            Route::post('/colaboradores/rechazarSolicitud' , 'rechazarSolicitud');

        });
    });


    Route::group(['middleware' => ['can:solicitudes , empleados']] , function(){
        Route::controller(ConsultarSolicitudes::class)->group(function(){
            Route::get('/rh/resumen' , 'index')->name('rh.index');
            Route::get('/rh/all' , 'all');
            Route::get('/rh/reporteEmpleado' , 'reporteEmpleado');
        });
    });


    // Route::controller(GestionarUsuarios::class)->group(function(){
    //     Route::get('/sistemas/gestionarUsuarios' , 'index')->name('gestionarUsuarios.index');
    //     Route::get('/sistemas/crearUsuario' , 'store');
    //     Route::get('/sistemas/all' , 'all');
    //     Route::post('/sistemas/update' , 'update');
    //     Route::post('/sistemas/delete' , 'destroy');
    //     Route::post('/sistemas/create' , 'create');
    // });


    Route::controller(Permisos::class)->group(function(){
        Route::get('/permisos/create' , 'create');
    });



});


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
