<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckIdleTimeout
{
    /**
     * Route yang dipanggil otomatis oleh halaman (polling), bukan oleh aksi
     * pengguna. Request seperti ini TIDAK boleh memperpanjang sesi — kalau
     * tidak, membiarkan halaman terbuka (mis. Monitor Sistem yang refresh tiap
     * 10 detik) membuat sesi tidak pernah berakhir karena idle.
     */
    private const PASSIVE_ROUTES = ['system-monitor.data'];

    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $lastActivity = session('last_activity');
            $idleTimeout = config('session.idle_timeout', 15) * 60; // Konversi ke detik

            if ($lastActivity && Carbon::now()->timestamp - $lastActivity > $idleTimeout) {
                Auth::logout();
                session()->flush();

                // Permintaan AJAX/JSON (polling, DataTables) tidak bisa memakai redirect
                // HTML; beri 401 agar klien bisa mengarahkan ke halaman login.
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json(['message' => 'Sesi Anda telah berakhir karena tidak ada aktivitas.'], 401);
                }

                return redirect()->route('login')->with('message', 'Sesi Anda telah berakhir karena tidak ada aktivitas.');
            }

            if (! $request->routeIs(self::PASSIVE_ROUTES)) {
                session(['last_activity' => Carbon::now()->timestamp]);
            }
        }

        return $next($request);
    }
}
