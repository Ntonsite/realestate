<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Property extends Model
{
    use SoftDeletes;
    protected $guarded = [];

    protected $casts = [
      'rental_frequency' => 'json',
      'near_by_name' => 'json',
      'offer' => 'json',
      'media' => 'json',
    ];


    public function user(){
        return $this->belongsTo(User::class);
    }

    public function properties(){
        return $this->hasMany(Notification::class);
    }
}
