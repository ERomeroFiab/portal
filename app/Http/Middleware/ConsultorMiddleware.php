<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ConsultorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if ( auth()->user()->rol !== "Consultor" ) {
            return redirect('/');
        }
        return $next($request);
    }
}
