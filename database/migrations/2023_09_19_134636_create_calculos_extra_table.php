<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Ramsey\Uuid\Type\Integer;

class CreateCalculosExtraTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calculos_extra', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_corte');
            $table->unsignedBigInteger('id_hora_extra');
            $table->integer('salario_total');
            $table->date('fecha_calculo');
            $table->timestamps();

            $table->foreign('id_corte')->references('id')->on('cortes');
            $table->foreign('id_hora_extra')->references('id')->on('horas_extra');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('calculos_extra');
    }
}
