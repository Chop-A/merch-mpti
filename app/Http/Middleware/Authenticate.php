<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Models\User;
use Exception;

class Authenticate
{
    public function handle($request, Closure $next, $role)
    {
        $token = $request->header('Authorization');
        if(!$token){
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Token required!'
                ], 401
            );
        } 
        try {
            $jwt = explode(' ',$token)[1];
            $credential = JWT::decode($jwt, new Key(env('JWT_KEY', 'SECRET'), 'SHA256'));
        } catch (\Firebase\JWT\ExpiredException $th) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Token expired!'
                ], 401
            );
        }
        $user = User::where('id',explode(':', $credential->sub)[0])->first();
        if($role){
            $condition = $user->hasRole($role);
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'Unauthorized!'
                    ], 403
                );
            $request->user = $user;
            return $next($request);
        }
    }
}
