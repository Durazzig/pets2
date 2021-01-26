<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    protected $fillable = [
        'paciente', 'propietario', 'hora', 'fecha', 'medico',
    ];

    public function bills()
    {
        return $this->hasMany(App\Bill::class);
    }
}
