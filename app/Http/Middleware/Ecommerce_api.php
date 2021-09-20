<?php

namespace App\Http\Middleware;

use Closure;

class Ecommerce_api
{

    public function handle($request, Closure $next)
    {
        //return $next($request);

       /* if(auth()->user()->isAdmin == 1){
            return $next($request);
        }
        return redirect('home')->with('error','You have not admin access');*/

       //check here api header and process
    }
}
