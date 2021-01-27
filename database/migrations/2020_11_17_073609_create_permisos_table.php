<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermisosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permisos', function (Blueprint $table) {
            $table->id();
            $table->string('empleado')->nullable();
            $table->string('area');
            $table->date('fecha_permiso');
            $table->date('fecha_permiso_final');
            $table->string('turno');
            $table->string('sustituto');
            $table->string('tipo_permiso');
            $table->string('aprobado')->default("nochecked");
            $table->string('motivo');
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
        Schema::dropIfExists('permisos');
    }
}
