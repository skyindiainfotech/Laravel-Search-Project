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
		'member_id',
		'username',
		'password',  
		'file',  
		'status',  
		'created_at',  
		'updated_at',  
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
	
	

	public function getMemberList($params) {
		
		$searchField = isset($params['search_field']) ? trim($params['search_field']) : '';
		$searchText = isset($params['search_text']) ? trim($params['search_text']) : '';
		$from_date = isset($params['from_date']) ? trim($params['from_date']) : '';
		$to_date = isset($params['to_date']) ? trim($params['to_date']) : '';
		$sortBy = isset($params['sort_by']) ? $params['sort_by'] : '';
		$sortOrd = isset($params['sort_order']) ? $params['sort_order'] : 'DESC';

		$selectArray[] = $this->table . '.*';
		$query = DB::table($this->table);   

		// filter query 

		if ($searchField != "" && $searchText != "") {
			
			if ($searchField == "all") {
				$query->where($this->table . ".username", 'LIKE', '%' . $searchText . '%');
			} else {
				$query->where($searchField, 'LIKE', '%' . $searchText . '%');
			}			
		}


		if ($from_date != "") {			
			$from_date = \Carbon\Carbon::createFromFormat('Y-m-d', $from_date)->format('Y-m-d');
			$query->whereDate($this->table . ".created_at", '>=', $from_date);			
		}

		if ($to_date != "") {
			$to_date = \Carbon\Carbon::createFromFormat('Y-m-d', $to_date)->format('Y-m-d');
			$query->whereDate($this->table . ".created_at", '<=', $to_date);			
		}

		// sort query
		if ($sortBy != "" && $sortOrd != "") {
			$query->orderBy($sortBy, $sortOrd);
		} else {
			$query->orderBy($this->table . '.id', 'DESC');
		}
				
		$query->select($selectArray);
		
		$record_per_page = (isset($params['record_per_page']) && $params['record_per_page'] != "" && $params['record_per_page'] > 0) ? $params['record_per_page'] : env('APP_RECORDS_PER_PAGE', 20);
		return $query->paginate($record_per_page);
		
	}

}