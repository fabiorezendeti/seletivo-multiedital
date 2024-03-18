<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Security\Audit as SecurityAudit;

class Audit
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

        $files = array_keys($request->file());

        $excepts = array_merge($files, [
            'password',
            'password_confirmation',
            '_token',
        ]);

        $r = Arr::except($request->all(), $excepts);

        $userAgent = $request->header('User-Agent') ?? 'Não identificado';        
        $referer = $request->header('referer') ?? 'Indefinido';

        SecurityAudit::create(
            [
                'user_id' => Auth::user()->id ?? 0,
                'uri' => $request->fullUrl(),
                'referer'   => Str::limit($referer, 210, '...'),
                'user_agent'    => Str::limit($userAgent, 210, '...'),
                'method' => $request->method(),
                'content' => @serialize($r),
                'ip' =>  $request->getClientIp()
            ]
        );

        return $next($request);
    }
}