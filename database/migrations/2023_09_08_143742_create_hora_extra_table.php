<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHoraExtraTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('horas_extra', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_empleado');
            $table->integer('diurnas');
            $table->integer('nocturnas');
            $table->integer('diurnas_descanso');
            $table->integer('nocturnas_descanso');
            $table->integer('diurnas_asueto');
            $table->integer('nocturnas_asueto');
            $table->integer('total_efectivo');
            $table->integer('total');
            $table->date('fecha_registro');
            $table->timestamps();

            $table->foreign('id_empleado')->references('id')->on('empleados');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hora_extra');
    }
}
