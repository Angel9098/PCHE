<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAreaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('areas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->unsignedBigInteger('empresa_id');
            $table->unsignedBigInteger('jefe_area')->nullable();
            $table->timestamps();

            $table->foreign('empresa_id')->references('id')->on('empresas');
        });
    }

    public function down()
    {
        Schema::table('areas', function (Blueprint $table) {
            // Eliminamos la clave foránea
            $table->dropForeign(['empresa_id']);
        });

        Schema::dropIfExists('areas');
    }
}
