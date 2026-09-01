<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class HeaderSetMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        $response->header('Content-Security-Policy',"script-src 'self' ajax.cloudflare.com challenges.cloudflare.com cdn.jsdelivr.net;style-src 'self' 'unsafe-inline' cdn.jsdelivr.net ckeditor.com fonts.googleapis.com fonts.gstatic.com;");
        $response->header('X-Frame-Options', 'deny', false);
        $response->header('X-Content-Type-Options', 'nosniff', false);
        $response->header('X-Permitted-Cross-Domain-Policies', 'none', false);
        $response->header('Referrer-Policy', 'strict-origin-when-cross-origin', false);
        $response->header('Cross-Origin-Embedder-Policy', 'unsafe-none', false);
        $response->header('Cross-Origin-Opener-Policy', 'same-origin', false);
        $response->header('Cross-Origin-Resource-Policy', 'same-origin', false);
        $response->header('X-XSS-Protection', '1; mode=block', false);
        $response->header('Strict-Transport-Security', 'max-age=31536000; includeSubdomains');
        $response->header('Permissions-Policy', 'geolocation=self');
        // $response->header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        // $response->header("Pragma: no-cache");
        return $response;
    }
}