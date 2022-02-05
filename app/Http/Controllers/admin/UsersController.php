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
use App\Models\Users;


class UsersController extends Controller
{

  public function __construct() 
  {

    // Module Slug
    $this->module_slug = "users";
    $this->table_name = TBL_USERS;

    // Model and Module name		
    $this->module = "users";
    $this->modelObj = new Users;

		// Links
		$this->list_url = url('/admin/'.$this->module_slug);

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
      $memberObj = Auth::guard('members')->user();

      $list_params = array(         
        'from_date' => $request->get('from_date'),
        'to_date' => $request->get('to_date'),
        'search_field' => $request->get('search_field'),
        'search_text' => $request->get('search_text'),
        'sort_by' => $request->get('sortBy'),
        'sort_order' => $request->get('sortOrd'),
        'record_per_page' => env('APP_RECORDS_PER_PAGE', 20),
        'member_id' => $request->get('member_id'),
      );

      $rows = $this->modelObj->getAdminList($list_params);

      $data['rows'] = $rows;
      $data['list_params'] = $list_params;
      $data['searchColumns'] = [
          'all' => 'All',
          $this->table_name.'.id' => 'ID',
          $this->table_name.'.username' => 'Username'
      ];
        
      $data['with_date'] = 1;
      $data['pageTitle'] = 'Users'; 
  
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
      return redirect($this->list_url);
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
      return view($this->view_base . '.index', $data);
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
     * Get user data by id
     * @param  void
     * @return $data
    */
    public function getUserdataByID(Request $request){

      $data = array();
      $reqArr = $request->all();
      $status = 0;
      $content = array();
      $msg = "Something went wrong, try again or may later.";
      
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

          $userObj = $this->modelObj->getAdminList(array('user_id' => $reqArr['id']));

          if(isset($userObj[0])){
            $userObj = $userObj[0];
            $file = '';
            $file_name = '';
            if(isset($userObj->file) && $userObj->file != ''){
              $file = $userObj->file;
              $fileInfo = pathinfo($file);
              $file_name = $fileInfo['filename'].".".$fileInfo['extension'] ;
            }
  
            $content = array(
              'username' => $userObj->username ?? '',
              'password' => $userObj->password ?? '',
              'file' => $file,
              'file_name' => $file_name,
              'first_name' => $userObj->first_name,
              'last_name' => $userObj->last_name,
              'email' => $userObj->email,
            );
            $status = "success";
            $msg = "user list availble.";
          }

      }

      $this->responseData['status'] = $status;
      $this->responseData['msg'] = $msg;
      if(!empty($content)) $this->responseData['content'] = $content;

      echo json_encode($this->responseData); exit; 
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      $model = $this->modelObj;
		  $modelObj = $model::find($id);

      if ($modelObj)
      {
        try
        {

          $uploadPath = public_path('uploads' . DIRECTORY_SEPARATOR . $this->module_slug . DIRECTORY_SEPARATOR . $modelObj->id . DIRECTORY_SEPARATOR);
          CommonTrait::deleteDirectory($uploadPath);
				  $modelObj->delete();

          session()->flash('success', "User has been successfully deleted");
          return redirect()->back();
        } catch (Exception $e)
        {

          session()->flash('error', "User can not deleted!");
          return redirect()->back();
        }
      } else
      {

			  session()->flash('error', "User can not deleted!");
			  return redirect()->back();
		  }
      return redirect()->back();
    }
}
