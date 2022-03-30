<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class FirstLoginMiddleware
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
        if ( auth()->user()->rol === "Cliente" && auth()->user()->first_login == null ) {
            return redirect()->route('cliente.first_login');
        }
        return $next($request);
    }
}
