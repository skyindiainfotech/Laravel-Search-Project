<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class MemberAuthenticate
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  Guard  $auth
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $guard = "members";


        if (!Auth::guard($guard)->check()) 
        {
            if ($request->ajax()) 
            {
                return response('Unauthorized.', 401);
            } 
            else 
            {
                return redirect()->guest('login');
            }
        }

        // check user permission

        $user = Auth::guard($guard)->user();


        $is_member = Session::get('is_member');
        
        if ($is_member == 1) {
            
        }else{

            if($user->status == '0'){

                return redirect('/');
            }
        }

        return $next($request);
    }
}
