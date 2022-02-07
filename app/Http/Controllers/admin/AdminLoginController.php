<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Validator;
use Socialite;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

/* Define Models */ 
use App\Models\User;
use App\Models\Admin;

class AdminLoginController extends Controller
{
    
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesUsers;
    
    public $redirectPath = 'admin';
    public $redirectAfterLogout = 'admin/login';
    public $loginPath = 'admin/login'; 


    /**
     * Create a new authentication controller instance.
     * @param void
     * @return void
     */
    public function __construct(){

        $this->middleware('guest_admin', ['except' => 'getLogout']);
        $this->badRequestMsg = __('messages.bad_request');
        $this->successEmailSentMsg = __('messages.email_sent_successfully');
        $this->responseData = array();
        $this->responseData['data'] = array();
        $this->responseData['status'] = 0;
        $this->responseData['msg'] = $this->badRequestMsg;
    }


    /**
     * Get Login Page FUnction
     * @param void
     * @return Response
     */
    public function getLogin(){

        $data = array();
        $data['pageTitle'] = __('messages.login');
        return view('admin.login', $data);
    }
    

    /**
     * Handle a login request to the application.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postLogin(Request $request){

        $status = 0;
        $msg = __('messages.credential_doesnt_match');
        
        $validator = Validator::make($request->all(), [
            'email' => 'required|email', 
            'password' => 'required',            
        ]);        
        
        // check validations
        if ($validator->fails()) {

            return redirect(url($this->loginPath))
                ->withErrors($validator)
                ->withInput($request->all());

        }else{

            if(Auth::guard('admins')->attempt(['email' => $request->get('email'),
             'password' => $request->get('password')])){

                $user = Auth::guard('admins')->user();
                
                if($user->status != 1){
                    Auth::guard('admins')->logout();
                    return redirect(url($this->loginPath))
                    ->withErrors(['error' =>  __('messages.account_not_active')])
                    ->withInput();
                }

                $status = 1;
                $msg =  __('messages.login_success');
                $user->last_login_at = \Carbon\Carbon::now();
                $user->save();                            
                return redirect(url($this->redirectPath));

            }else{

                return redirect(url($this->loginPath))
                    ->withErrors(['error' => __('messages.invalid_login_details')])
                    ->withInput();
            }            
        }
    }    


    public function getLogout()
    {
        Auth::guard('admins')->logout();
        return redirect($this->redirectAfterLogout);
    } 


    
              
}
