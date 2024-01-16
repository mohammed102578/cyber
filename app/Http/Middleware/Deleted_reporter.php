<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Deleted_reporter 
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
 

     public function handle(Request $request, Closure $next, ...$guard)
     {
        if(Auth::guard('reporter')->user()->deleted ==1){
            return redirect()->route('reporter_deleted');

        }
        return $next($request);
     }

}
