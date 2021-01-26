<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    protected $fillable = [
        'id',
        'provider_id',
        'folio',
        'fecha',
        'fecha_entrega',
        'monto',
        'empleado',
        'imagen'
    ];
    
    public function provider()
    {
        return $this->belongsTo('App\Provider','provider_id');
    }

    public function empleados(){
        return $this->belongsTo('App\User','empleado');
    }
}
