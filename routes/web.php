<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RH\ConsultarSolicitudes;
use App\Http\Controllers\Colaboradores\RegistroVacaciones;
use App\Http\Controllers\Colaboradores\GestionarSolicitudes;


Route::redirect('/', '/login');


Route::middleware(['auth'])->group(function(){

    Route::controller(RegistroVacaciones::class)->group(function(){
        Route::get('/colaboradores/registroVacaciones', 'index')->name('registroVacaciones.index');
        Route::post('/colaboradores/registroVacaciones' , 'store');
    });


    Route::controller(GestionarSolicitudes::class)->group(function(){
        Route::get('/colaboradores/gestionarSolicitudes' , 'index')->name('gestionarSolicitudes.index');
        Route::get('/colaboradores/getSolicitudes' , 'getSolicitudes');
        Route::post('/colaboradores/getSolicitudUser' , 'getSolicitudUser');
        Route::post('/colaboradores/aprobarSolicitud' , 'aprobarSolicitud');
        Route::post('/colaboradores/rechazarSolicitud' , 'rechazarSolicitud');
    });


    Route::controller(ConsultarSolicitudes::class)->group(function(){
        Route::get('/rh/resumen' , 'index')->name('rh.index');
        Route::get('/rh/all' , 'all');
        Route::get('/rh/reporteEmpleado' , 'reporteEmpleado');
    });
});


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
