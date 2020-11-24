<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;

class JWTMiddleware
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
        $message = '';

        try{
            JWTAuth::parseToken()->authenticate();
            return $next($request);
        }catch(\Tymon\JWTAuth\Exceptions\TokenExpiredException $e){
            //do something if token expired
            $message = 'token expired';
        }catch(\Tymon\JWTAuth\Exceptions\TokenInvalidException $e){
            //do something if token invalid
            $message = 'token invalid';
        }catch(\Tymon\JWTAuth\Exceptions\JWTException $e){
            //do something if token is not present
            $message = 'provide token';
        }
        return response()->json([
            'success' => false,
            'message' => $message
        ]);
    }
}
