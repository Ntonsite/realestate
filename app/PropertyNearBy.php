<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PropertyNearBy extends Model
{
    protected $guarded = [];

    public function property(){
        return $this->hasMany(Property::class);
    }
}
