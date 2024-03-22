<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('empleados', function (Blueprint $table) {
            $table->id();
            $table->string('numeroEmpleado')->null();
            $table->string('colaborador')->null();
            $table->string('fechaIngreso')->null();
            $table->unsignedBigInteger('idPlantel')->null();
            $table->unsignedBigInteger('idPuesto')->null();
            $table->unsignedBigInteger('idArea')->null();
            $table->unsignedBigInteger('idJefe')->null();
            $table->unsignedBigInteger('idUser')->null();
            $table->string('correo')->null();
            $table->boolean('estatus');

            $table->foreign('idPlantel')->references('id')->on('planteles');
            $table->foreign('idUser')->references('id')->on('users');
            $table->foreign('idPuesto')->references('id')->on('puestos');
            $table->foreign('idArea')->references('id')->on('areas');
            $table->foreign('idJefe')->references('id')->on('empleados');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empleados');
    }
};
