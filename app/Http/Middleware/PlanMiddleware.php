<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PlanMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user=Auth::user();
        if($user->check_plan == 'free')
        {
         $request->merge(['post_type'=>'free']);
         return $next($request);
        }
        else{
            $request->merge(['post_type'=>'paid']);
            return $next($request);
        }

    }
}
