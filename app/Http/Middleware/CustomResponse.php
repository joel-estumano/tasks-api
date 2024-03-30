<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Closure;

class CustomResponse
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
        $resp = $next($req);
        $formattedResponse = [
            'data' => $resp->getOriginalContent(),
            'timestamp' => now(),
            'path' => $req->path(),
            'status' => $resp->status()
        ];
        return response()->json($formattedResponse, $resp->status());
    }
}
