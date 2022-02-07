<?php
namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Validator;

/* Define Function */ 
use App\CommonTrait;


/* Define Models */ 
use App\Models\Admin;
use App\Models\CelebrityCategory;
use App\Models\Celebrity;

class AdminController extends Controller
{    
    use CommonTrait;

    /**
     * Get Dashboard Page Function
     * @param  void
     * @return $data
    */
    public function index(){

        $data = array(); 
        $data['pageTitle'] = __('messages.dashboard');
        return view('admin.dashboard',$data);
    }    
    
}
