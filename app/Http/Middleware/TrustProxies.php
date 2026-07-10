<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Illuminate\Http\Request;

class TrustProxies extends Middleware
{
    /**
     * The trusted proxies for this application.
     *
     * Dibaca dari config app.trusted_proxies (env TRUSTED_PROXIES,
     * dipisah koma). Kosong berarti tidak ada proxy yang dipercaya.
     *
     * @var array<int, string>|string|null
     */
    protected $proxies;

    protected function proxies()
    {
        $proxies = config('app.trusted_proxies');

        if (blank($proxies)) {
            return null;
        }

        if ($proxies === '*' || $proxies === '**') {
            return $proxies;
        }

        return array_values(array_filter(array_map('trim', explode(',', $proxies))));
    }

    /**
     * The headers that should be used to detect proxies.
     *
     * @var int
     */
    protected $headers =
        Request::HEADER_X_FORWARDED_FOR |
        Request::HEADER_X_FORWARDED_HOST |
        Request::HEADER_X_FORWARDED_PORT |
        Request::HEADER_X_FORWARDED_PROTO |
        Request::HEADER_X_FORWARDED_AWS_ELB;
}
