<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckIdleTimeout
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $lastActivity = session('last_activity');
            $idleTimeout = config('session.idle_timeout', 15) * 60; // Konversi ke detik

            if ($lastActivity && Carbon::now()->timestamp - $lastActivity > $idleTimeout) {
                Auth::logout();
                session()->flush();

                return redirect()->route('login')->with('message', 'Sesi Anda telah berakhir karena tidak ada aktivitas.');
            }

            session(['last_activity' => Carbon::now()->timestamp]);
        }

        return $next($request);
    }
}
