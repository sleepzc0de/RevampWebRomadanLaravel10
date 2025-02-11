<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;

class PreventAdminModification
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->route('user')) {
            $user = User::find($request->route('user'));
            if ($user && $user->hasRole('ADMINISTRATOR')) {
                return redirect()->route('users.index')
                    ->with('failed', 'User dengan role ADMINISTRATOR tidak dapat dimodifikasi.');
            }
        }

        return $next($request);
    }
}
