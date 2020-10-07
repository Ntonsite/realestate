<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'name',
    ];

    public function user(){
        return $this->hasOne(User::class);
    }
}
