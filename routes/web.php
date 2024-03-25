<?php

use Illuminate\Support\Facades\Route;
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
    });
});


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
