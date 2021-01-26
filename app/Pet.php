<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    protected $fillable = [
        'name', 'species', 'raze', 'age', 'status', 'owner_id'
    ];

    public function owner()
    {
        return $this->belongsTo('App\Owner','owner_id');
    }
}
