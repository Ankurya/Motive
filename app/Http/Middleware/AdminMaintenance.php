<?php

namespace App\Http\Middleware;

use Closure;   
use DB;
class AdminMaintenance
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
        $get_admin=DB::table('app_setting')->first();
	    if($get_admin->maintenance_status == '1') {
			//return response()->json(['error' => 'Work is in progress,please wait'], 405);
			return response()->json(['message' => 'We are busy upgrading our systems to find you an even better MoTiv! We apologise for any inconvenience and we appreciate your patience whilst we help you Discover Your MoTiv!'], 405);
            
        }
        return $next($request);
    }
}
