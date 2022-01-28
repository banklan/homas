<?php

namespace App\Http\Middleware;

use Closure;

class PortfolioCount
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
        if(auth('api')->user()->service->is_premium){
            return $next($request);
        }else{
            if(auth('api')->user()->service->portfolio->count() < 3){
                return $next($request);
            }else{
                return;
            }
        }
    }
}
