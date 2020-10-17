<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Property extends Model
{
    use SoftDeletes;
    protected $guarded = [];


    public function user(){
        return $this->belongsTo(User::class);
    }

    public function properties(){
        return $this->hasMany(Notification::class);
    }
}
