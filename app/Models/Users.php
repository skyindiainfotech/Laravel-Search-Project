<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Cviebrock\EloquentSluggable\Sluggable;
use Carbon\Carbon;

class Users extends Model
{

	use Sluggable;
    /**
	* The attributes that are mass assignable.
	*
	* @var array
	*/
	 protected $guard = "users";
	
    protected $fillable = [
		'username',
		'password',  
    ];
	
	protected $table = TBL_USERS;
	protected $table_alias = 'u';


	public function sluggable()
    {
        return [
            'username' => [
                'source' => 'name',
				'onUpdate' => true
            ]
        ];
    }
	
	

}