<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PropertyRentalFrequency extends Model
{
    protected $guarded = [];

    public function property(){
        return $this->hasOne(Property::class);
    }
}
