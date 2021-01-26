<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Owner extends Model
{
    protected $fillable = [
        'name', 'address', 'phone',
    ];

    public function pets()
    {
        return $this->hasMany('App\Pet');
    }
}
