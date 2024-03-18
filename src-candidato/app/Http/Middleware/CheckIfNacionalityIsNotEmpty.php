<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckIfNacionalityIsNotEmpty
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {           
        if (!Auth::user()) return $next($request);        
        if (Auth::user()->is_foreign === null && ($request->getRequestUri() !== '/user/profile' and $request->getMethod() === 'GET'))
            return redirect('/user/profile')->with('error','Você deve informar se é estrangeiro ou não!');
        return $next($request);        
    }
}
