<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;

class AdminAuthentication
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if( ! $request->session()->get('adminLoggedIn') ){
            if($request->ajax()){
                $exception = new AuthenticationException();
                return response()->json(['message' => $exception->getMessage()], 401);
            } else {
                abort(401);
            }            
        }

        return $next($request);
    }
}
