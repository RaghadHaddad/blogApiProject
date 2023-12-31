<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JWTMiddleware extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
    //     try {
    //         $jwt = JWTAuth::parseToken()->authenticate();
    //         if(!$jwt)
    //         {
    //             return response()->json(['message' => 'Unauthenticated'],401);
    //         }
    //     } catch (JWTException $e) {
    //         if($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
    //             return response()->json(['status' => 'Token is invalid']);
    //         }elseif($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
    //             return response()->json(['status' => 'Token is Expired']);
    //         }else{
    //             return response()->json(['status' => 'Authorization Token not found']);
    //         }
    //     }
    //         return $next($request);

    // }

    try {
        $user = JWTAuth::parseToken()->authenticate();
    }
     catch (JWTException $e)
     {
        if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
            return response()->json(['status' => 'Token is Invalid']);
        }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
            return response()->json(['status' => 'Token is Expired']);
        }else{
            return response()->json(['status' => 'Authorization Token not found']);
        }
    }
    return $next($request);
}
}
