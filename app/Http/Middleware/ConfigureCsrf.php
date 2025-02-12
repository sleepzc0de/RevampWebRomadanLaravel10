<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ConfigureCsrf
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Configure the XSRF-TOKEN cookie
        $config = config('session');

        if ($response->headers->has('Set-Cookie')) {
            $cookies = $response->headers->getCookies();
            foreach ($cookies as $cookie) {
                if ($cookie->getName() === 'XSRF-TOKEN') {
                    $response->headers->setCookie(
                        new \Symfony\Component\HttpFoundation\Cookie(
                            'XSRF-TOKEN',
                            $cookie->getValue(),
                            $cookie->getExpiresTime(),
                            $config['path'],
                            $config['domain'],
                            $config['secure'],
                            true, // Set HttpOnly to true
                            false,
                            $config['same_site'] ?? 'lax'
                        )
                    );
                }
            }
        }

        return $response;
    }
}
