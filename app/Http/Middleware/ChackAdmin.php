<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ChackAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if( $request->user()->role == 'admin' )
        {
          
          return $next($request);
     //or redirect to somewhere
        }
    
        else {
            return response()->json(['message'=> 'Unauthorized'],401);

        }
    }
}
