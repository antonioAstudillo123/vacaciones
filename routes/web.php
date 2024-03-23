<?php

use App\Http\Controllers\Colaboradores\RegistroVacaciones;
use Illuminate\Support\Facades\Route;


Route::redirect('/', '/login');


Route::middleware(['auth'])->group(function(){

    Route::controller(RegistroVacaciones::class)->group(function(){
        Route::get('/colaboradores/registroVacaciones', 'index')->name('registroVacaciones.index');
        Route::post('/colaboradores/registroVacaciones' , 'store');
    });
});


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
