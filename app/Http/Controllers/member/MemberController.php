<?php
namespace App\Http\Controllers\member;

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
use App\Models\Members;

class MemberController extends Controller
{    
    use CommonTrait;

    /**
     * Get Landing Page Function
     * @param  void
     * @return $data
    */
    public function index(){

        $data = array(); 
        $data['pageTitle'] =  __('home');
        return view('member.dashboard',$data);
    }

    
}
