<?php
declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;
use Formatter;

final readonly class HasValidCaptcha
{
    public function handle(Request $request, Closure $next): Response
    {
        // Attempt to resolve the Turnstile code from the request. If it doesn't
        // exist then return an HTTP 400 response.

        if(Formatter::identity()['cloudflare']=='active'){

            $turnstileCode = $request->input('cf-turnstile-response') ?? abort(400);

            if (!$this->turnstileCodeIsValid($turnstileCode)) {
                abort(400);
            }
        }
        

        return $next($request);
    }

    /**
     * Make an HTTP call to the Turnstile API to verify the code.
     */
    private function turnstileCodeIsValid(string $turnstileCode): bool
    {
        return Http::post(
            url: 'https://challenges.cloudflare.com/turnstile/v0/siteverify',
            data: [
                'secret' => config('services.cloudflare.turnstile.secret_key'),
                'response' => $turnstileCode,
            ]
        )->json('success');
    }
}