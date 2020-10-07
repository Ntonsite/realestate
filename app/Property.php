<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    protected $guarded = [];

    public function favorite(){
        return $this->hasMany(Favorite::class);
    }

    public function propertyNearBy(){
        return $this->belongsToMany(PropertyNearBy::class);
    }

    public function propertyRentalFrequency(){
        return $this->belongsToMany(PropertyRentalFrequency::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
