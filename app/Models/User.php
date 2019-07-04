<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\MustVerifyEmail as MustVerifyEmailTrait;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Auth;

class User extends Authenticatable implements MustVerifyEmailContract
{
    use MustVerifyEmailTrait;

    use Notifiable {
        notify as protected laravelNotify;
    }

    public function notify($instance){

        if ($this->id === Auth::id()) {
            return ; 
        }

        if (method_exists($instance , 'toDatabase')) {
            $this->increment('notification_count' , 1);
        }

        $this->laravelNotify($instance);
    }


    public function markAsRead(){

        $this->notification_count = 0;
        $this->save();
        $this->unreadNotifications->markAsRead();
    }


    // public function 

    // 接下来我们在用户模型中新增与话题模型的关联：
    public function topics(){

        return $this->hasMany(Topic::class);
    }


    public function replies(){

        return $this->hasMany(Reply::class);
    }


    public function isAuthorOf($models){

        return $this->id === $models->user_id;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','avatar','introduction'
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
}
