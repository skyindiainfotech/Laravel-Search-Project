<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Cviebrock\EloquentSluggable\Sluggable;
use Carbon\Carbon;

class Members extends Authenticatable
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
		'first_name',
		'last_name',
		'email',
		'password',
		'token_key',
		'otp', 
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
	

	/**
     * Get List For Admin Panel.
     *
     * @param  array  $params - contains all filters parameters
     * @return array  - contains data and paginators.
     */
	public function getAdminList($params) {
		
		$searchField = isset($params['search_field']) ? trim($params['search_field']) : '';
		$searchText = isset($params['search_text']) ? trim($params['search_text']) : '';
		$from_date = isset($params['from_date']) ? trim($params['from_date']) : '';
		$to_date = isset($params['to_date']) ? trim($params['to_date']) : '';
		$sortBy = isset($params['sort_by']) ? $params['sort_by'] : '';
		$sortOrd = isset($params['sort_order']) ? $params['sort_order'] : 'DESC';
		$member_id = isset($params['id']) ? $params['id'] : '';

		$selectArray[] = $this->table . '.*';
		$query = DB::table($this->table);   

		// filter query 

		if ($searchField != "" && $searchText != "") {
			
			if ($searchField == "all") {
				$query->where($this->table . ".email", 'LIKE', '%' . $searchText . '%')
				->where($this->table . ".first_name", 'LIKE', '%' . $searchText . '%')
				->where($this->table . ".last_name", 'LIKE', '%' . $searchText . '%');
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

		if($member_id != ''){
			$query->where($this->table . ".id",$member_id);
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