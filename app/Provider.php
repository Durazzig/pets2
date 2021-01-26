<?php

namespace App;

 
use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    protected $fillable = [
        'name', 'phone',
    ];

    public function bills()
    {
        return $this->hasMany('App\Bill');
    }
}
