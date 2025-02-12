<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    private const PEPPER = 'mwdun-2937h-_(&)HG)*GOIUNJ)HG)*(&F*^D&^S%#$E^RGYOIBJNPOKMO}:}?}"?:>{K)OJ()*YT^&DRFYGUIHT&^R%E%EDYF2025';
    private const HASH_ALGO = 'sha256';

    public function showLoginForm()
    {
        return view('auth.romadan_login');
    }

    // public function login(Request $request)
    // {
    //     $request->validate([
    //         'email' => 'required|email',
    //         'password' => 'required'
    //     ]);

    //     $user = User::where('email', $request->email)->first();

    //     if (!$user) {
    //         return back()->withErrors([
    //             'email' => 'The provided credentials do not match our records.',
    //         ])->withInput($request->except('password'));
    //     }

    //     // Recreate the password hash using stored salt
    //     $peppered = hash_hmac(self::HASH_ALGO, $request->password . $user->salt, self::PEPPER);

    //     // Verify using Laravel's built-in Hash check
    //     if (Hash::check($peppered, $user->password)) {
    //         Auth::login($user);
    //         return redirect()->route('home');
    //     }

    //     return back()->withErrors([
    //         'email' => 'The provided credentials do not match our records.',
    //     ])->withInput($request->except('password'));
    // }

    public function login(Request $request)
{
    try {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'captcha' => 'required',
            '_token' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()
                ->withErrors(['email' => 'The provided credentials do not match our records.'])
                ->withInput($request->except('password'));
        }

        $peppered = hash_hmac(self::HASH_ALGO, $request->password . $user->salt, self::PEPPER);

        if (Hash::check($peppered, $user->password)) {
            // Regenerate session setelah login berhasil
            $request->session()->regenerate();

            Auth::login($user);

            // Set secure session configuration
            config([
                'session.secure' => true,
                'session.http_only' => true,
                'session.same_site' => 'lax'
            ]);

            // Set cookie dengan parameter yang sesuai
            $cookie = cookie(
                'laravel_session',      // nama
                session()->getId(),      // value
                60 * 24 * 30,           // duration (30 hari dalam menit)
                '/',                    // path
                null,                   // domain
                true,                   // secure
                true                    // httpOnly
            );

            Log::debug('Login successful', [
                'user_id' => $user->id,
                'new_session_id' => session()->getId()
            ]);

            return redirect()
                ->route('home')
                ->withCookie($cookie);
        }

        return back()
            ->withErrors(['email' => 'The provided credentials do not match our records.'])
            ->withInput($request->except('password'));

    } catch (\Exception $e) {
        Log::error('Login error', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return back()
            ->withErrors(['error' => 'An error occurred during login. Please try again.'])
            ->withInput($request->except('password'));
    }
}

public function logout(Request $request)
{
    Auth::logout();

    // Invalidate dan regenerate session
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    // Hapus cookie dengan parameter yang sesuai
    $cookie = cookie(
        'laravel_session',    // nama
        '',                   // value (kosong untuk menghapus)
        -1,                   // duration (negatif untuk expire)
        '/',                  // path
        null,                 // domain
        true,                 // secure
        true                  // httpOnly
    );

    return redirect('/')
        ->withCookie($cookie);
}


}
