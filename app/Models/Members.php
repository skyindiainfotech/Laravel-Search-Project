<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Cviebrock\EloquentSluggable\Sluggable;
use Carbon\Carbon;

class Members extends Model
{

	use Sluggable;
    /**
	* The attributes that are mass assignable.
	*
	* @var array
	*/
	 protected $guard = "members";
	
    protected $fillable = [
		'email',
		'username',
		'first_name',
		'last_name',
		'email',
		'password',
		'token_key',  
		'status', 
    ];
	
	protected $table = TBL_MEMBERS;
	protected $table_alias = 'm';


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