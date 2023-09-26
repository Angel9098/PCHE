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
        Schema::create('calculos_horas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_corte');
            $table->unsignedBigInteger('empleado_id');
            $table->integer('jefe_area');
            $table->integer('salario_mensual');
            $table->integer('total_horas');
            $table->integer('salario_neto');
            $table->integer('salario_total');
            $table->date('fecha_calculo');
            $table->timestamps();

            $table->foreign('id_corte')->references('id')->on('cortes');

            $table->foreign('empleado_id')->references('id')->on('empleados');
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
