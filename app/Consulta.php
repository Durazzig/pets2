<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Consulta extends Model
{
    protected $fillable = [
        'id',
        'fecha',
        'medico_id',
        'hora_llegada',
        'hora_atencion',
        'hora_termino',
        'propietario',
        'mascota',
        'peso',
        'edad',
        'raza',
        'servicio',
        'finalizado',
    ];

    public function medico()
    {
        return $this->belongsTo('App\User','medico_id');
    }

}
