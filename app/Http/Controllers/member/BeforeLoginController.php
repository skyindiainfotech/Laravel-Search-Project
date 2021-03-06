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
        $data['pageTitle'] =  __('messages.home');
        return view('member.landing',$data);
    }

    
    /**
     * Get Login Page Function
     * @param  void
     * @return $data
    */
    public function login(){

        $data = array(); 
        $data['pageTitle'] = __('messages.sign_in');
        return view('member.beforelogin.login',$data);
    }

    /**
     * Get Sign-Up Page Function
     * @param  void
     * @return $data
    */
    public function signUp(){

        $data = array(); 
        $data['pageTitle'] =  __('messages.sign_up');
        return view('member.beforelogin.sign-up',$data);
    }

    
    /**
     * Get Forgot Password Page Function
     * @param  void
     * @return $data
    */
    public function forgotPassword(){

        $data = array(); 
        $data['pageTitle'] = __('messages.forgot_password');
        return view('member.beforelogin.forgot-password',$data);
    }

     
    /**
     * Get Reset Password Page Function
     * @param  void
     * @return $data
    */
    public function resetPassword($token){

        $data = array(); 
        $data['pageTitle'] = __('messages.reset_password');
        $memberObj = Members::where('token_key',$token)->first();
        if(isset($memberObj->id) && $memberObj->id > 0){
            $data['token'] = $token;
            return view('member.beforelogin.reset-password',$data);
        }else{
            session()->flash('error', __('messages.token_expired') );
            return redirect(url('/login'));
        }
    }


    /**
     * Function for make change password of member
     * @param  void
     * @return $data
    */
    public function processResetPassword(Request $request){

        $status = 0;
        $msg = __('messages.went_wrong');
        $redirctUrl = redirect()->back();

        $requestArr = $request->all();
        $validator = Validator::make($requestArr, [
            'password' => 'required|min:8|max:16|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
            'confirm_password' => 'required|same:password',            
            'token_key' => 'required',            
        ]);

        if ($validator->fails()) {
        
            return redirect()->back()
            ->withErrors($validator)
            ->withInput($request->all());
            
        } else {

            $memberObj = Members::where('token_key',$requestArr['token_key'])->first();
            if(isset($memberObj->id) && $memberObj->id > 0){
                $memberObj->password = bcrypt($requestArr['password']);
                $memberObj->save();
                $status = 1;
                $msg = __('messages.password_reset_success');
                $redirctUrl = redirect(url('/login'));
            }
            else{
                $status = 0;
                $msg = __('messages.token_expired');
            }
        }

        session()->flash($status == 1 ? 'success' : 'error', $msg );
        return $redirctUrl;
    }


    /**
     * Get Forgot Password Function that send a mail with reset password link
     * @param  $request
     * @return $data
    */
    public function processForgotPassword(Request $request){
            
        $status = 0;
        $msg = __('messages.went_wrong');
        $redirctUrl = redirect()->back();

        $requestArr = $request->all();
        $validator = Validator::make($requestArr, [
            'email' => 'required|email',            
        ]);

        if ($validator->fails()) {
        
            return redirect()->back()
            ->withErrors($validator->errors())
            ->withInput($request->all());
        } else {
            
            $email = isset($requestArr['email'])? $requestArr['email'] : '';            

            $memberObj = Members::where('email', '=', $email)->where('status', '=', '0')->get()->first();
            if(isset($memberObj->id) && $memberObj->id > 0){

                $reset_passsword_token = md5($memberObj->id);
                $to = $memberObj->email;
                $name = ucfirst($memberObj->first_name) . ' ' . ucfirst($memberObj->last_name);
                $resetPasswordUrl = url('reset-password') . '/' . $reset_passsword_token;

                // Send password reset link details in email
                $emailData = array(
                    'email_name' => 'ACCOUNT-FORGOT-PASSWORD-EMAIL',
                    'to_email' => $memberObj->email,
                    'NAME' => $name,
                    'PASSWORDRESETLINK' => $resetPasswordUrl,         
                );
                $is_sent_email = CommonTrait::sendBulkEmailUsingSMTP($emailData);
                                   
                $memberObj->token_key = $reset_passsword_token;
                $memberObj->save();
                
                $status = 1;
                $msg = __('messages.reset_link_on_mail');
                $redirctUrl = redirect('/login');
            }
        }
        session()->flash($status == 1 ? 'success' : 'error', $msg );
        return $redirctUrl;
    }


    /**
     * Get 2-step Verification Page Function
     * @param  void
     * @return $data
    */
    public function verificationPage(){

        $data = array(); 
        $data['pageTitle'] = __('messages.2_step_verification');
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
        $msg = __('messages.went_wrong');
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
                $msg = __('messages.verified_account');
                $redirctUrl = redirect(url('/login'));
            }else{
                $status = 0;
                $msg = __('messages.invalid_details_provided');
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
            'password' => 'required|min:8|max:16|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
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
                session()->flash('success', __('messages.registration_success'));
                return redirect($url);
            }
        }

        session()->flash('error', __('messages.went_wrong'));
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
                    session()->flash('error', __('messages.account_is_not_active'));
                    return redirect(url('/login'));
                }

               // session()->flash('success', 'Login Successfully.');        
                return redirect(url('/member/dashboard'));
            }else{
                session()->flash('error', __('messages.invalid_login_details')); 
                return redirect(url('/login'));
            }            
         }
        session()->flash('error', __('messages.went_wrong')); 
        return redirect(url('/login'));
     }
   
   
    /**
     * Get Change Password Page Function
     * @param  void
     * @return $data
    */
    public function changePassword(){

        $data = array(); 
        $data['pageTitle'] =  __('messages.change_password');
        return view('member.beforelogin.change-password',$data);
    }


    /**
     * Function for make change password of member
     * @param  void
     * @return $data
    */
    public function processChangePassword(Request $request){

        if(Auth::guard('members')->user()){
            $status = 0;
            $msg = __('messages.went_wrong');
            $redirctUrl = redirect()->back();

            $requestArr = $request->all();
            $validator = Validator::make($requestArr, [
                'password' => 'required|min:8|max:16|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
                'confirm_password' => 'required|same:password',            
            ]);

            if ($validator->fails()) {
            
                return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->all());
                
            } else {

                if(Auth::guard('members')->check()){
                    $memberObj = Auth::guard('members')->user();
                    $memberObj->password = bcrypt($requestArr['password']);
                    $memberObj->save();
                    $status = 1;
                    $msg = "Password changed successfully.";
                    $redirctUrl = redirect(url('/member'));
                }          
            }

            session()->flash($status == 1 ? 'success' : 'error', $msg );
            return $redirctUrl;
        }else{
            session()->flash('error', __('messages.went_wrong')); 
            return redirect(url('/login'));
        }
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
