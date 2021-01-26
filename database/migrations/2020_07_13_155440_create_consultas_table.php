<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConsultasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consultas', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->string('medico_id')->nullable();
            $table->string('hora_llegada')->nullable();
            $table->string('hora_atencion')->nullable();
            $table->string('hora_termino')->nullable();
            $table->string('propietario');
            $table->string('mascota');
            $table->double('peso');
            $table->string('edad');
            $table->string('raza');
            $table->string('servicio');
            $table->integer('finalizado')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('consultas');
    }
}
