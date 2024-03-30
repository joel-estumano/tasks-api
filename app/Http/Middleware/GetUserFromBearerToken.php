<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Closure;

class GetUserFromBearerToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $req, Closure $next)
    {
        $token = $req->bearerToken();
        $accessToken = PersonalAccessToken::findToken($token);
        if ($accessToken) {
            $user = $accessToken->tokenable;
            $req->merge(['user' => $user]);
        }
        return $next($req);
    }
}
