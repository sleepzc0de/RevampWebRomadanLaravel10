<?php

namespace App\Http\Controllers;

use App\Models\Login\LoginModel;
use App\Models\User;
use App\Services\CaptchaService;
use App\Services\PasswordService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    private const MAX_LOGIN_ATTEMPTS = 5;

    private const LOCKOUT_SECONDS = 60;

    protected $captchaService;

    protected $passwordService;

    public function __construct(CaptchaService $captchaService, PasswordService $passwordService)
    {
        $this->captchaService = $captchaService;
        $this->passwordService = $passwordService;
    }

    public function showLoginForm()
    {
        return view('auth.romadan_login', [
            'captcha' => $this->captchaService->createCaptcha(),
            'gambar' => LoginModel::inRandomOrder()->first(),
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'captcha' => 'required',
            'captcha_token' => 'required',
        ]);

        $throttleKey = $this->throttleKey($request);

        if (RateLimiter::tooManyAttempts($throttleKey, self::MAX_LOGIN_ATTEMPTS)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            return back()->withErrors([
                'email' => "Terlalu banyak percobaan login. Silakan coba lagi dalam {$seconds} detik.",
            ])->withInput($request->except('password'));
        }

        // Validasi CAPTCHA dulu
        if (! $this->captchaService->validateCaptcha($request->captcha, $request->captcha_token)) {
            RateLimiter::hit($throttleKey, self::LOCKOUT_SECONDS);

            return back()->withErrors([
                'captcha' => 'CAPTCHA validation failed. Please try again.',
            ])->withInput($request->except('password'));
        }

        $user = User::where('email', $request->email)->first();

        if ($user && $this->passwordService->verify($request->password, $user->salt, $user->password)) {
            RateLimiter::clear($throttleKey);

            Auth::login($user);

            // Cegah session fixation: buat session ID baru setelah autentikasi
            $request->session()->regenerate();

            activity('auth')
                ->causedBy($user)
                ->withProperties(['ip' => $request->ip()])
                ->log('Login berhasil');

            return redirect()->route('home');
        }

        RateLimiter::hit($throttleKey, self::LOCKOUT_SECONDS);

        if ($user) {
            activity('auth')
                ->causedBy($user)
                ->withProperties(['ip' => $request->ip()])
                ->log('Login gagal (password salah)');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->except('password'));
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            activity('auth')
                ->causedBy(Auth::user())
                ->withProperties(['ip' => $request->ip()])
                ->log('Logout');
        }

        Auth::logout();

        // Invalidate dan regenerate session user yang login
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Hapus cookie dengan parameter yang sesuai, info sesuai urutan
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

    private function throttleKey(Request $request): string
    {
        return Str::lower((string) $request->input('email')).'|'.$request->ip();
    }
}
