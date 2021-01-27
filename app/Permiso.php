<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permiso extends Model
{
    protected $fillable = [
        'empleado', 'area', 'fecha_permiso', 'fecha_permiso_final', 'turno', 'sustituto', 'tipo_permiso', 'aprobado', 'motivo'
    ];

    public function empleados(){
        return $this->belongsTo('App\User','empleado');
    }

    public function sustitutos(){
        return $this->belongsTo('App\User','sustituto');
    }
}
