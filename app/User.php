<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends \TCG\Voyager\Models\User implements MustVerifyEmail
{
    use HasApiTokens, Notifiable;

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = [
        'name',
        'role_id',
        'account_type',
        'name',
        'email',
        'first_name',
        'last_name',
        'phone',
        'country',
        'image',
        'business_email',
        'status',
        'password',
    ];

    /**
    * The attributes that should be hidden for arrays.
    *
    * @var array
    */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
    * The attributes that should be cast to native types.
    *
    * @var array
    */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'account_type' => 'json',
    ];

    public function ads()
    {
        return $this->hasMany(Ad::class);
    }

    public function  role(){
        return $this->belongsToMany(Role::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }


    public function properties(){
        return $this->hasMany(Notification::class);
    }
}
