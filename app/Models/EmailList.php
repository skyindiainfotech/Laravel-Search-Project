<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EmailList extends Model
{
	
	/**
	* The attributes that are mass assignable.
	*
	* @var array
	*/
	protected $fillable = [
		'template_id',
		'email_name',
		'title',
		'subject',
		'sender_email',
		'email_text',
		'status'
	];

		
	protected $table = TBL_EMAIL_LIST;
	protected $table_alias = 'el';

	public function getAdminList($params) {
		
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
				
				$query->
					where($this->table . ".email_name", 'LIKE', '%' . $searchText . '%')->
					orWhere($this->table . ".title", 'LIKE', '%' . $searchText . '%')->
					orWhere($this->table . ".subject", 'LIKE', '%' . $searchText . '%')->
					orWhere($this->table . ".sender_email", 'LIKE', '%' . $searchText . '%');

			}else {
				
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
		
		$query->leftJoin('tbl_email_template', 'tbl_email_template.id', '=', 'template_id');
		$selectArray[] = 'tbl_email_template.template_name as template_name';
		
		$query->select($selectArray);
		
		$record_per_page = (isset($params['record_per_page']) && $params['record_per_page'] != "" && $params['record_per_page'] > 0) ? $params['record_per_page'] : env('APP_RECORDS_PER_PAGE', 20);
		return $query->paginate($record_per_page);
		
	}
	

	/**
     * Find By Name Function
     * @param $email_name
     * @return array
     */	
	public static function findByName($email_name){
		
		$selectArray[] = TBL_EMAIL_LIST . '.*';
		$query = DB::table(TBL_EMAIL_LIST);
		
		$query->where(TBL_EMAIL_LIST . '.email_name', '=', $email_name);
		$query->where(TBL_EMAIL_LIST . '.status', '=', '1');
		
		$query->orderBy(TBL_EMAIL_LIST . '.id', 'DESC');
		
		$query->select($selectArray);
		return $query->get()->first();
		
	}
}
