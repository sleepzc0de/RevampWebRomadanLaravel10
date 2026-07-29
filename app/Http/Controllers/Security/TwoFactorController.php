<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\Controller;
use App\Services\PasswordService;
use App\Services\TwoFactorService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TwoFactorController extends Controller
{
    private const SESSION_PENDING_SECRET = '2fa_setup_secret';

    public function __construct(
        private readonly TwoFactorService $twoFactor,
        private readonly PasswordService $passwordService,
    ) {}

    public function index(Request $request): View
    {
        $user = $request->user();
        $pendingSecret = $request->session()->get(self::SESSION_PENDING_SECRET);

        $qrCodeSvg = null;
        if ($pendingSecret) {
            $qrCodeSvg = $this->twoFactor->qrCodeSvg(
                config('twofactor.issuer'),
                $user->email,
                $pendingSecret
            );
        }

        return view('backend.security.two-factor.index', [
            'user' => $user,
            'pendingSetup' => (bool) $pendingSecret,
            'manualKey' => $pendingSecret,
            'qrCodeSvg' => $qrCodeSvg,
        ]);
    }

    /**
     * Mulai proses aktivasi: generate secret baru, simpan sementara di
     * session sampai dikonfirmasi dengan kode yang valid (belum disimpan
     * ke database supaya tidak ada secret "menggantung" jika dibatalkan).
     */
    public function enable(Request $request): RedirectResponse
    {
        $request->session()->put(self::SESSION_PENDING_SECRET, $this->twoFactor->generateSecretKey());

        return redirect()->route('two-factor.index');
    }

    public function cancel(Request $request): RedirectResponse
    {
        $request->session()->forget(self::SESSION_PENDING_SECRET);

        return redirect()->route('two-factor.index');
    }

    public function confirm(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        $secret = $request->session()->get(self::SESSION_PENDING_SECRET);

        if (! $secret) {
            return redirect()->route('two-factor.index')->with('failed', 'Sesi aktivasi 2FA sudah kedaluwarsa, silakan mulai lagi.');
        }

        if (! $this->twoFactor->verifyCode($secret, $request->code)) {
            return redirect()->route('two-factor.index')->with('failed', 'Kode verifikasi salah, silakan coba lagi.');
        }

        $recoveryCodes = $this->twoFactor->generateRecoveryCodes();

        $user = $request->user();
        $user->two_factor_secret = $this->twoFactor->encryptSecret($secret);
        $user->two_factor_recovery_codes = $this->twoFactor->encryptRecoveryCodes($recoveryCodes);
        $user->two_factor_confirmed_at = now();
        $user->save();

        $request->session()->forget(self::SESSION_PENDING_SECRET);

        activity('user')
            ->causedBy($user)
            ->performedOn($user)
            ->log('Mengaktifkan autentikasi dua faktor (2FA)');

        return redirect()->route('two-factor.index')
            ->with('success', '2FA berhasil diaktifkan!')
            ->with('new_recovery_codes', $recoveryCodes);
    }

    public function disable(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        $user = $request->user();

        if (! $this->passwordService->verify($request->password, $user->salt, $user->password)) {
            return redirect()->route('two-factor.index')->with('failed', 'Password salah.');
        }

        $user->two_factor_secret = null;
        $user->two_factor_recovery_codes = null;
        $user->two_factor_confirmed_at = null;
        $user->save();

        activity('user')
            ->causedBy($user)
            ->performedOn($user)
            ->log('Menonaktifkan autentikasi dua faktor (2FA)');

        return redirect()->route('two-factor.index')->with('success', '2FA berhasil dinonaktifkan.');
    }

    public function regenerateRecoveryCodes(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        $user = $request->user();

        if (! $user->hasTwoFactorEnabled()) {
            return redirect()->route('two-factor.index');
        }

        if (! $this->passwordService->verify($request->password, $user->salt, $user->password)) {
            return redirect()->route('two-factor.index')->with('failed', 'Password salah.');
        }

        $recoveryCodes = $this->twoFactor->generateRecoveryCodes();
        $user->two_factor_recovery_codes = $this->twoFactor->encryptRecoveryCodes($recoveryCodes);
        $user->save();

        activity('user')
            ->causedBy($user)
            ->performedOn($user)
            ->log('Membuat ulang kode pemulihan 2FA');

        return redirect()->route('two-factor.index')
            ->with('success', 'Kode pemulihan baru berhasil dibuat!')
            ->with('new_recovery_codes', $recoveryCodes);
    }
}
