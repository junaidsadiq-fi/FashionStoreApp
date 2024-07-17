<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateApi {
  public function handle(Request $request, Closure $next): Response {
    $token = $request->bearerToken();

    if ($token === env('API_TOKEN')) {
      return $next($request);
    }

    return response()->json(['message' => 'Unauthorized'], 401);
  }
}
