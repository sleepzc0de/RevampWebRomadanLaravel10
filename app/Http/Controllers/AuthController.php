<?php

namespace App\Http\Controllers;

use App\Models\Login\LoginModel;
use App\Models\User;
use App\Services\CaptchaService;
use App\Services\PasswordService;
use App\Services\TwoFactorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    private const MAX_LOGIN_ATTEMPTS = 5;

    private const LOCKOUT_SECONDS = 60;

    private const SESSION_2FA_PENDING = '2fa_pending_user_id';

    protected $captchaService;

    protected $passwordService;

    protected $twoFactor;

    public function __construct(CaptchaService $captchaService, PasswordService $passwordService, TwoFactorService $twoFactor)
    {
        $this->captchaService = $captchaService;
        $this->passwordService = $passwordService;
        $this->twoFactor = $twoFactor;
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

            // Cegah session fixation: buat session ID baru setelah password terverifikasi,
            // baik lanjut ke 2FA maupun langsung masuk.
            $request->session()->regenerate();

            if ($user->hasTwoFactorEnabled()) {
                $request->session()->put(self::SESSION_2FA_PENDING, $user->id);

                activity('auth')
                    ->causedBy($user)
                    ->withProperties(['ip' => $request->ip()])
                    ->log('Password benar, menunggu verifikasi 2FA');

                return redirect()->route('two-factor.challenge');
            }

            Auth::login($user);

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

    public function twoFactorChallenge(Request $request)
    {
        if (! $request->session()->has(self::SESSION_2FA_PENDING)) {
            return redirect()->route('login');
        }

        return view('auth.two_factor_challenge');
    }

    public function twoFactorCancel(Request $request)
    {
        $request->session()->forget(self::SESSION_2FA_PENDING);

        return redirect()->route('login');
    }

    public function twoFactorVerify(Request $request)
    {
        $userId = $request->session()->get(self::SESSION_2FA_PENDING);

        if (! $userId) {
            return redirect()->route('login');
        }

        $request->validate([
            'code' => 'required|string|max:20',
        ]);

        $throttleKey = '2fa:'.$userId.'|'.$request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, self::MAX_LOGIN_ATTEMPTS)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            return back()->withErrors([
                'code' => "Terlalu banyak percobaan. Silakan coba lagi dalam {$seconds} detik.",
            ]);
        }

        $user = User::find($userId);

        if (! $user || ! $user->hasTwoFactorEnabled()) {
            $request->session()->forget(self::SESSION_2FA_PENDING);

            return redirect()->route('login');
        }

        $code = trim((string) $request->code);
        $isValidTotp = preg_match('/^\d{6}$/', $code)
            && $this->twoFactor->verifyCode($this->twoFactor->decryptSecret($user->two_factor_secret), $code);
        $isValidRecovery = ! $isValidTotp && $this->twoFactor->consumeRecoveryCode($user, $code);

        if (! $isValidTotp && ! $isValidRecovery) {
            RateLimiter::hit($throttleKey, self::LOCKOUT_SECONDS);

            activity('auth')
                ->causedBy($user)
                ->withProperties(['ip' => $request->ip()])
                ->log('Verifikasi 2FA gagal');

            return back()->withErrors([
                'code' => 'Kode verifikasi salah.',
            ]);
        }

        RateLimiter::clear($throttleKey);
        $request->session()->forget(self::SESSION_2FA_PENDING);

        Auth::login($user);
        $request->session()->regenerate();

        activity('auth')
            ->causedBy($user)
            ->withProperties(['ip' => $request->ip(), 'method' => $isValidRecovery ? 'recovery_code' : 'totp'])
            ->log('Login berhasil (2FA)');

        return redirect()->route('home');
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
