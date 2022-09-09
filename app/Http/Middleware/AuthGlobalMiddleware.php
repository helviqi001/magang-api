<?php

namespace App\Http\Middleware;

use Closure;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;

class AuthGlobalMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        if (empty($token)) {
            return response()->json([
                'data' => null,
                'message' => 'Token not provided.',
                'success' => false
            ], 401);
        }

        try {
            $credentials = JWT::decode($request->bearerToken(), new Key(env('JWT_SECRET'), 'HS256'));
        } catch (\Exception $e) {
            return response()->json([
                'data' => null,
                'message' => $e->getMessage(),
                'success' => false
            ], 401);
        }

        $request->authGlobal = $credentials;

        return $next($request);
    }
}
