<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignkeyEmpleado extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('empleados', function(Blueprint $table){
            $table->unsignedBigInteger('horario_id')->after('area_id');

            $table->foreign('horario_id')->references('id')->on('horarios');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('empleados', function(Blueprint $table){
            $table->dropForeign(['horario_id']);

            $table->dropColumn('horario_id');

        });
    }
}
