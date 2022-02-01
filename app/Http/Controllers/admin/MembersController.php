<?php

namespace App\Http\Controllers\admin;

use Validator;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;

/* Define CommonTrait */ 
use App\CommonTrait;

/* Define Models */ 
use App\Models\Members;


class MembersController extends Controller
{
    
  public function __construct() 
  {

    // Module Slug
    $this->module_slug = "members";
    $this->table_name = TBL_MEMBERS;

    // Model and Module name		
    $this->module = "members";
    $this->modelObj = new Members;

		// Links
		$this->list_url = route($this->module_slug . ".index");
		$this->add_url = ADMIN_HOME_URL . '/'. $this->module_slug .'/create';
		$this->edit_url = ADMIN_HOME_URL. '/'. $this->module_slug .'/{id}/edit';

  		// View
		$this->view_base = 'admin.'.$this->module_slug;
	}



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      $data = array();
      $list_params = array(         
        'from_date' => $request->get('from_date'),
        'to_date' => $request->get('to_date'),
        'search_field' => $request->get('search_field'),
        'search_text' => $request->get('search_text'),
        'sort_by' => $request->get('sortBy'),
        'sort_order' => $request->get('sortOrd'),
        'record_per_page' => env('APP_RECORDS_PER_PAGE', 20)
      );

      $rows = $this->modelObj->getAdminList($list_params);

      $data['rows'] = $rows;
      $data['list_params'] = $list_params;
      $data['searchColumns'] = [
          'all' => 'All',
          $this->table_name.'.id' => 'ID',
          $this->table_name.'.name' => 'Name',
          $this->table_name.'.username' => 'Username'
          
      ];
        
      $data['with_date'] = 1;
      $data['pageTitle'] = __('members'); 
      return view($this->view_base . '.index', $data);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
      $data = array();   
      return view($this->add_url, $data);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
 
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      $data = array();
      return view($this->view_base . '.edit', $data);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      $data = array();
      return view($this->view_base . '.show', $data);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
      $requestArr = $request->all();
      return redirect($this->list_url);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      return redirect($this->list_url);
    }
}
