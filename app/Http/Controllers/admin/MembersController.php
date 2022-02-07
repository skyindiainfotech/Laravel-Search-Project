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
          $this->table_name.'.first_name' => __('messages.first_name'),
          $this->table_name.'.last_name' => __('messages.last_name'),
          $this->table_name.'.email' => __('messages.email'),
      ];
        
      $data['with_date'] = 1;
      $data['pageTitle'] = __('messages.members'); 
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
     * Get Member data by id
     * @param  void
     * @return $data
    */
    public function getMemberdataByID(Request $request){

      $data = array();
      $reqArr = $request->all();
      $status = 0;
      $content = array();
      $msg = __('messages.went_wrong');
      
      $validator = Validator::make($request->all(), [
        'id' => 'required|numeric',
      ]);
  
      if ($validator->fails())
      {
          $this->responseData['status'] = 0;
          $this->responseData['msg'] = $validator->messages()->first();
          echo json_encode($this->responseData); exit;
      } else
      { 

          $memberObj = $this->modelObj->getAdminList(array('id' => $reqArr['id']));

          if(isset($memberObj[0])){
            $memberObj = $memberObj[0];
  
            $status = $memberObj->status == 0 ? 'Active' : 'Inactive';
            $content = array(
              'first_name' => $memberObj->first_name,
              'last_name' => $memberObj->last_name,
              'email' => $memberObj->email,
              'status' => $status,
              'updated_at' => $memberObj->updated_at,
            );
            $status = "success";
            $msg = __('messages.member_list_availble');
          }

      }

      $this->responseData['status'] = $status;
      $this->responseData['msg'] = $msg;
      if(!empty($content)) $this->responseData['content'] = $content;

      echo json_encode($this->responseData); exit; 
    }


    /**
     * Change Member status to Active/Inactive.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function changeStatus($id)
    {
      $status = 0;
      $msg = __('messages.went_wrong');

      if(isset($id) && $id > 0){
        $memberObj = Members::where('id',$id)->first();

        $status = 0;
        $msg = __('messages.user_not_found');
        if(isset($memberObj->id)){
          $memberObj->status = $memberObj->status == '0' ? '1' : '0';
          $memberObj->save();

          $status = 1;
          $msg = __('messages.user_status_changed');
        }
      }
      session()->flash($status == 1 ? 'success' : 'error', $msg );
      return redirect()->back();
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
