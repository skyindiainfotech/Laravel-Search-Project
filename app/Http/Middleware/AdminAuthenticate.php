<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AdminAuthenticate
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
        $guard = "admins";


        if (!Auth::guard($guard)->check()) 
        {
            if ($request->ajax()) 
            {
                return response('Unauthorized.', 401);
            } 
            else 
            {
                return redirect()->guest('admin/login');
            }
        }

        // check user permission
        $user = Auth::guard($guard)->user();
        
        return $next($request);
    }
}
