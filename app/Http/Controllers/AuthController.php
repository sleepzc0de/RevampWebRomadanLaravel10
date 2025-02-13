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

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])->withInput($request->except('password'));
        }

        // Recreate the password hash using stored salt
        $peppered = hash_hmac(self::HASH_ALGO, $request->password . $user->salt, self::PEPPER);

        // Verify using Laravel's built-in Hash check
        if (Hash::check($peppered, $user->password)) {
            Auth::login($user);
            return redirect()->route('home');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->except('password'));
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
