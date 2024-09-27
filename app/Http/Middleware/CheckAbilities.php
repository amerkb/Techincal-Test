<?php

namespace App\Http\Middleware;

use App\ApiHelper\ApiResponseCodes;
use App\ApiHelper\ApiResponseHelper;
use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class CheckAbilities
{
    /**
     * Handle an incoming request.
     *
     * @param  mixed  ...$roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        try {
            $token = $request->header('Authorization');
            if (! $token) {
                return response()->json(['status' => 'Authorization Token not found'], 401);
            }

            $request->headers->set('auth-token', (string) $token, true);
            $request->headers->set('Authorization', 'Bearer '.$token, true);

            $user = JWTAuth::parseToken()->authenticate();

            $hasRole = false;
            foreach ($roles as $role) {
                if ($user->user_type === intval($role)) {
                    $hasRole = true;
                    break;
                }
            }

            if (! $hasRole) {
                return ApiResponseHelper::sendMessageResponse('You are not authorized to access this resource', ApiResponseCodes::FORBIDDEN, false);

            }
        } catch (\Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return response()->json(['status' => 'Token is Invalid'], 401);
            } elseif ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return response()->json(['status' => 'Token is Expired'], 401);
            } else {
                return response()->json(['status' => 'Authorization Token not found'], 401);
            }
        }

        return $next($request);
    }
}
