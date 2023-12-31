<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class HeaderChecker
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
        if(!in_array($request->headers->get('accept'), ['application/json', 'Application/Json']))
            return response()->json(['message' => 'Unauthenticated.'], 401);
        return $next($request);
    }
}
