<?php

namespace App\Http\Middleware;

use App\Helpers\VisitorHelper;
use App\Models\backend\MenuVisitor\VisitorModel;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Catat kunjungan halaman frontend untuk dipantau admin di backend CMS.
 * Pencatatan dilakukan di terminate() (setelah response dikirim ke browser)
 * agar tidak menambah latensi pada request pengunjung.
 */
class LogVisitor
{
    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }

    public function terminate(Request $request, Response $response): void
    {
        // Hanya catat kunjungan halaman penuh (GET, bukan AJAX/fragment fetch)
        if (! $request->isMethod('GET') || $request->ajax() || $request->wantsJson()) {
            return;
        }

        $userAgent = (string) $request->userAgent();

        try {
            VisitorModel::create([
                'ip_address' => $request->ip(),
                'user_agent' => mb_substr($userAgent, 0, 500),
                'browser' => $this->detectBrowser($userAgent),
                'platform' => $this->detectPlatform($userAgent),
                'device_type' => $this->detectDeviceType($userAgent),
                'url' => mb_substr($request->path(), 0, 500),
                'route_name' => optional($request->route())->getName(),
                'referrer' => mb_substr((string) $request->headers->get('referer', ''), 0, 500) ?: null,
                'session_id' => $request->hasSession() ? $request->session()->getId() : null,
                'is_bot' => VisitorHelper::isBot($userAgent),
            ]);
        } catch (Throwable $e) {
            report($e);
        }
    }

    private function detectBrowser(string $ua): string
    {
        return match (true) {
            str_contains($ua, 'Edg/') => 'Edge',
            str_contains($ua, 'OPR/') || str_contains($ua, 'Opera') => 'Opera',
            str_contains($ua, 'Chrome/') && ! str_contains($ua, 'Chromium') => 'Chrome',
            str_contains($ua, 'Firefox/') => 'Firefox',
            str_contains($ua, 'Safari/') && ! str_contains($ua, 'Chrome') => 'Safari',
            str_contains($ua, 'MSIE') || str_contains($ua, 'Trident/') => 'Internet Explorer',
            default => 'Lainnya',
        };
    }

    private function detectPlatform(string $ua): string
    {
        return match (true) {
            str_contains($ua, 'Android') => 'Android',
            str_contains($ua, 'iPhone') || str_contains($ua, 'iPad') || str_contains($ua, 'iPod') => 'iOS',
            str_contains($ua, 'Windows') => 'Windows',
            str_contains($ua, 'Macintosh') || str_contains($ua, 'Mac OS') => 'macOS',
            str_contains($ua, 'Linux') => 'Linux',
            default => 'Lainnya',
        };
    }

    private function detectDeviceType(string $ua): string
    {
        return match (true) {
            str_contains($ua, 'iPad') || str_contains($ua, 'Tablet') => 'Tablet',
            str_contains($ua, 'Mobi') || str_contains($ua, 'Android') => 'Mobile',
            default => 'Desktop',
        };
    }
}
