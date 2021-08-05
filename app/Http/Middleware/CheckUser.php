<?php

namespace App\Http\Middleware;

use Closure;

class CheckUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard='web')
    {
        if(!Auth()->guard('web')->check()) {
            return redirect(url('website/login'));  
        }
        return $next($request);
    }
}
