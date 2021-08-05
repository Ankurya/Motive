<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Passport\HasApiTokens;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
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
    ];


    public function userSetting(){
         return $this->hasOne('App\Models\UserSettings');
    }
    
    public function notificationList(){
         return $this->hasMany('App\Models\NotificationList');
    }
    
    public function contactUs(){
         return $this->hasOne('App\Models\ContactUs');
    }
      
    public function friendList(){
         return $this->hasMany('App\Models\FriendList');
    }
    public function commentList(){
         return $this->hasMany('App\Models\CommentList');
    }

    public function istagramImages(){
         return $this->hasMany('App\Models\IstagramImages');
    }
}
