<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Firebase\JWT\ExpiredException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Service\JWTService;

class ApiAuth
{
    protected JWTService $tokenService;

    public function __construct(JWTService $tokenService)
    {
        $this->tokenService = $tokenService;
    }

    public function handle(Request $request, Closure $next): mixed
    {
        $token = $request->header('Authorization') ?: $request->cookie('X-Pius-Auth');

        if (!$token) {
            return response()->json(['errors' => 'Unauthorized'], 401);
        }

        try {
            $decodedToken = $this->tokenService->decodeToken($token);
            $roles = $decodedToken->roles;

            if (isset($decodedToken->expired) && strtotime($decodedToken->expired) < time()) {
                $refreshToken = $decodedToken->refresh ?? null;
                $newToken = $this->tokenService->refreshToken($refreshToken);

                if ($newToken) {
                    $response = $next($request);
                    $response->headers->set('Authorization', $newToken);
                    $response->withCookie(cookie('X-Pius-Auth', $newToken));
                    return $response;
                } else {
                    return response()->json(['errors' => 'Unauthorized'], 401);
                }
            }

            if ($this->userHasPermission($roles, $request->path())) {
                return $next($request);
            } else {
                return response()->json(['error' => 'Forbidden'], 403);
            }
        } catch (ExpiredException $e) {
            return response()->json(['errors' => 'Token expired'], 401);
        } catch (Exception $e) {
            Log::channel('errorlog')->error($e->getMessage());
            return response()->json(['errors' => 'Unauthorized'], 401);
        }
    }

    private function userHasPermission($roles, $endpoint): bool
    {
        return contains($roles, 'admin') || contains($roles, 'service');
    }
}
