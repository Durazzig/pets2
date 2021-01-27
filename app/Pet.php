<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Pet extends Model
{
    protected $fillable = [
        'name', 'species', 'raze', 'age', 'status', 'owner_id'
    ];

    public function owner()
    {
        return $this->belongsTo('App\Owner','owner_id');
    }

    public function getAgeAttribute()
    {
        return Carbon::createFromFormat('Y-m-d', $this->dob)->diffInYears();
    }
}
