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

class BeforeLoginController extends Controller
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
        return view('member.landing',$data);
    }

    
    /**
     * Get Login Page Function
     * @param  void
     * @return $data
    */
    public function login(){

        $data = array(); 
        $data['pageTitle'] = "Sign-in";
        return view('member.beforelogin.login',$data);
    }

    /**
     * Get Sign-Up Page Function
     * @param  void
     * @return $data
    */
    public function signUp(){

        $data = array(); 
        $data['pageTitle'] = "Sign-up";
        return view('member.beforelogin.sign-up',$data);
    }

    
    /**
     * Get Forgot Password Page Function
     * @param  void
     * @return $data
    */
    public function forgotPassword(){

        $data = array(); 
        $data['pageTitle'] = "Sign-up";
        return view('member.beforelogin.forgot-password',$data);
    }


    /**
     * Get 2-step Verification Page Function
     * @param  void
     * @return $data
    */
    public function verificationPage(){

        $data = array(); 
        $data['pageTitle'] = "2-step Verification";
        return view('member.beforelogin.verification',$data);
    }


    /**
     * Get 2-step Verification process Function
     * @param  void
     * @return $data
    */
    public function processVerification(Request $request){

        $data = array(); 
        $status = 0;
        $msg = "Something went wrong, try again or may later.";
        $redirctUrl = redirect()->back();

        $requestArr = $request->all();

        // check validations
        $validator = Validator::make($requestArr, [
            'token_key' => 'required',
            'code' => 'required|min:6|max:6',
        ]);

        // if fails redirect to sign-up page with error messages.
		if ($validator->fails()) 
		{
            return redirect()->back()
            ->withErrors($validator->errors())
            ->withInput($request->all());
		}else
		{
            $memObj = Members::select('id','email','status')->where('token_key',base64_decode($requestArr['token_key']))->where('otp',$requestArr['code'])->get()->first();
            if(isset($memObj->id) && $memObj->id > 0){
                $memObj->status = 0;
                $memObj->token_key = '';
                $memObj->otp = '';
                $memObj->save();
                $status = 1;
                $msg = "Your account has been verified, you can login.";
                $redirctUrl = redirect(url('/login'));
            }else{
                $status = 0;
                $msg = "Invalid details provided.";
            }
        }

        session()->flash($status == 1 ? 'success' : 'error', $msg );
        return $redirctUrl;
    }
    
   
    
    /**
     * Function for register user into system
     * @param  void
     * @return $data
    */
    public function processRegister(Request $request){
        
        $data = array(); 
        $requestArr = $request->all();
        
        // check validations
        $validator = Validator::make($requestArr, [
            'email' => 'required|min:2|email|unique:' . TBL_MEMBERS,
            'first_name' => 'required|min:2|max:255',
            'last_name' => 'required|min:2|max:255',
            'password' => 'required|min:8|max:16',
            'confirm_password' => 'required|same:password',
            'terms_and_conditions' => 'required',
        ]);

        // if fails redirect to sign-up page with error messages.
		if ($validator->fails()) 
		{
            return redirect(url('/sign-up'))
            ->withErrors($validator->messages())
            ->withInput($request->all());
		}else
		{

            // inti data for create user
            $password = bcrypt($requestArr['password']);
            $insert_data = array(
                'first_name' => $requestArr['first_name'],
                'last_name' => $requestArr['last_name'],
                'email' => $requestArr['email'],
                'password' => $password,
                'status' => 1     // 0:Active // 1:Inactive 
            );


            $obj = Members::create($insert_data);

            if(isset($obj->id))
            {
                // set OTP setup
                //@TODO: Send Mail Functionality here and random number OTP
                $insert_data['token_key'] = CommonTrait::randomString();
                $insert_data['otp'] =  CommonTrait::randomString(6,'int');
                $url = url('/verification').'?token='. base64_encode($insert_data['token_key']);
                $emailData = array(
                    'email_name' => 'SENT-OTP',
                    'to_email' => $requestArr['email'],
                    'LINK' => $url,
                    'OTP' => $insert_data['otp'],
                );
                
                $is_email_sent = CommonTrait::sendBulkEmailUsingSMTP($emailData);
                $obj->token_key = $insert_data['token_key'];
                $obj->otp = $insert_data['otp'];
                $obj->save();
                
                session()->flash('secured_email', $requestArr['email']);
                session()->flash('success', 'Registration successfull, please verify your account.');
                return redirect($url);
            }
        }

        session()->flash('error', 'Something went wrong, try again or may later.');
        return redirect(url('/sign-up'));
    }


    /**
     * Function for Login user into system
     * @param  void
     * @return $data
    */
    public function processLogin(Request $request){
         
        // check validations
         $validator = Validator::make($request->all(), [
             'email' => 'required|email', 
             'password' => 'required',            
         ]);        

         // if fails redirect to login page.
         if ($validator->fails()) {
             return redirect(url($this->loginPath))
                 ->withErrors($validator)
                 ->withInput($request->all());
 
         }else{
 

            if (Auth::guard('members')->attempt(['email' => $request->get('email'),
             'password' => $request->get('password')])) {

                //Check for active status
                $user = Auth::guard('members')->user();
                if($user->status == 1){
                    Auth::guard('members')->logout();
                    session()->flash('error', 'Your account is not activated');
                    return redirect(url('/login'));
                }

                session()->flash('success', 'Login Successfully.');        
                return redirect(url('/member/dashboard'));
            }else{
                session()->flash('error', 'Invalid login details.'); 
                return redirect(url('/login'));
            }            
         }
        session()->flash('error', 'Something went wrong, try again or may later.'); 
        return redirect(url('/login'));
     }
   
   
    /**
     * Function for Logout member from system
     * @param  void
     * @return $data
    */
    public function getLogout()
    {
        Auth::guard('members')->logout();
        return redirect(url('/'));
    } 
}
