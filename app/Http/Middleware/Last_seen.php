<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Last_seen 
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
 

    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {

            if ($guard == "admin" && Auth::guard($guard)->check()) {
                Auth::guard($guard)->user()->update(['last_seen_at' => now()]);
            }elseif($guard == "reporter" && Auth::guard($guard)->check()) {
         
                Auth::guard($guard)->user()->update(['last_seen_at' => now()]);
            }elseif($guard == "corporate" && Auth::guard($guard)->check()) {
                Auth::guard($guard)->user()->update(['last_seen_at' => now()]);
            }
        }

        return $next($request);
    }



}
