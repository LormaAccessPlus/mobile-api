<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AccessApiMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Extract Authorization header
        $authHeader = $request->header('Authorization');

        if (! $authHeader || ! str_starts_with($authHeader, 'Bearer ')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $token = substr($authHeader, 7);

        // Check token in cache
        $studentId = cache()->get("access_api_token:{$token}");

        if (! $studentId) {
            return response()->json(['message' => 'Invalid or expired token'], 401);
        }

        // Attach student_id to request for downstream use
        $request->merge(['student_id' => $studentId]);

        // Log API access for monitoring
        Log::info('ACCESS API Request', [
            'student_id' => $studentId,
            'endpoint'   => $request->path(),
            'method'     => $request->method(),
            'ip'         => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Continue request
        $response = $next($request);

        // Add security headers
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        return $response;
    }
}
