<?php

use App\Http\Controllers\RegistroVacaciones;
use Illuminate\Support\Facades\Route;


Route::controller(RegistroVacaciones::class)->group(function () {
    Route::get('/', 'index');
});
