<?php

namespace App\Http\Controllers\member;

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
		$this->list_url = url('/member/'.$this->module_slug);
		$this->add_url = url('/member') . '/'. $this->module_slug ;
		$this->edit_url = url('/member'). '/'. $this->module_slug .'/{id}/edit';

  		// View
		$this->view_base = 'member.'.$this->module_slug;
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

      $rows = $this->modelObj->getMemberList($list_params);

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
      $status = 0;
      $msg = "Something went wrong, try again or may later.";
      $redirctUrl = redirect()->back();

      $requestArr = $request->all();
      $validator = Validator::make($request->all(), [
        'username' => 'required|alpha_num|min:2|max:20|unique:' . $this->table_name,
        'password' => 'required|min:2|max:15',
        'file' => 'required|mimes:xls,xlsx'
      ]);

      if($validator->fails())
      {
        return redirect($this->add_url)->withErrors($validator)->withInput();
      
      } else {

        $memberObj = Auth::guard('members')->user();
            
        $model = $this->modelObj;
        $insertArr = array(
          'username' => $requestArr['username'],
          'password' => $requestArr['password'],
          'member_id' => $memberObj->id,
          'status' => 0,
        );

			  $obj = $model::create($insertArr);

        if(isset($obj->id) && $obj->id > 0){
          
          $status = 1;
          $msg = "User details uploaded.";

          if($request->hasFile('file')) 
          {
            $xls_file = $request->file('file');
            // file name contains the user_id, member_id and current time.
            $filename  =  $obj->id.'-'.$memberObj->id.'-'.time().'.'.$xls_file->getClientOriginalExtension();                
                
            $uploadPath = public_path('uploads' . DIRECTORY_SEPARATOR . $this->module_slug . DIRECTORY_SEPARATOR . $obj->id);
            CommonTrait::deleteDirectory($uploadPath);
                                                                                                                          
            $fullpath = $uploadPath.DIRECTORY_SEPARATOR.$filename;
            $filename = CommonTrait::getFilename($fullpath, $filename);                                                                
            
            $xls_file->move($uploadPath, $filename);
            $file_url = url('uploads' . '/' . $this->module_slug . '/' . $obj->id . '/' . $filename);
            $obj->file = $file_url;
            $obj->save();                

            $status = 1;
            $msg = "User details and file are uploaded successfully.";
            $redirctUrl = redirect($this->list_url);
          }
        }
      } 
      session()->flash($status == 1 ? 'success' : 'error', $msg );
      return $redirctUrl;  
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
