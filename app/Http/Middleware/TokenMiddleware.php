<?php

namespace App\Http\Middleware;

use Closure;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;

class TokenMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        if (empty($token)) {
            return response()->json([
                'success' => false,
                'message' => 'Token not provided.',
                'data' => null,
            ], 401);
        }
        try {
            $credentials = JWT::decode($request->bearerToken(), new Key(env('JWT_SECRET') . 'token', 'HS256'));
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => null,
            ], 401);
        }

        $request->token = $credentials;

        return $next($request);
    }
}
