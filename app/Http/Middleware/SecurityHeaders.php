<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');

        // Only set CSP if not in development
        if (app()->environment('production')) {
            $response->headers->set('Content-Security-Policy',
                "default-src 'self'; " .
                "script-src 'self' 'unsafe-inline' 'unsafe-eval' https:;" .
                "style-src 'self' 'unsafe-inline' https:; " .
                "img-src 'self' data: https:; " .
                "font-src 'self' https: data:; " .
                "connect-src 'self' https:; " .
                "frame-ancestors 'self';"
            );
        }

        return $response;
    }
}
