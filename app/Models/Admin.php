<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{   
    use Notifiable;

    protected $guard = "admin";
    protected $table = TBL_ADMIN_USERS;
    

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_type',
        'name', 
		'email', 
        'password', 
        'status',
        'last_login_at',
        'remember_token',
    ];
    

}
