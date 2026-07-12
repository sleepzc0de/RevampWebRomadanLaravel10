<?php

namespace App\Services;

use App\Models\User;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use PragmaRX\Google2FA\Google2FA;

/**
 * Bungkus pragmarx/google2fa (TOTP) + bacon/bacon-qr-code (render QR lokal,
 * tanpa memanggil layanan pihak ketiga — secret tidak pernah keluar server).
 */
class TwoFactorService
{
    private const RECOVERY_CODE_COUNT = 8;

    private Google2FA $engine;

    public function __construct()
    {
        $this->engine = new Google2FA;
    }

    public function generateSecretKey(): string
    {
        return $this->engine->generateSecretKey();
    }

    /**
     * Render QR setup sebagai SVG inline (bukan URL gambar eksternal).
     */
    public function qrCodeSvg(string $issuer, string $holder, string $secret): string
    {
        $otpAuthUrl = $this->engine->getQRCodeUrl($issuer, $holder, $secret);

        $renderer = new ImageRenderer(
            new RendererStyle(200),
            new SvgImageBackEnd
        );

        return (new Writer($renderer))->writeString($otpAuthUrl);
    }

    public function verifyCode(string $secret, string $code): bool
    {
        // window=1 -> toleransi ±30 detik untuk selisih jam perangkat
        return (bool) $this->engine->verifyKey($secret, $code, 1);
    }

    public function encryptSecret(string $secret): string
    {
        return Crypt::encryptString($secret);
    }

    public function decryptSecret(string $encrypted): string
    {
        return Crypt::decryptString($encrypted);
    }

    /**
     * @return array<int, string>
     */
    public function generateRecoveryCodes(): array
    {
        return collect()
            ->times(self::RECOVERY_CODE_COUNT, fn () => Str::upper(Str::random(4).'-'.Str::random(4)))
            ->all();
    }

    public function encryptRecoveryCodes(array $codes): string
    {
        return Crypt::encryptString(json_encode($codes));
    }

    /**
     * @return array<int, string>
     */
    public function decryptRecoveryCodes(?string $encrypted): array
    {
        if (! $encrypted) {
            return [];
        }

        return json_decode(Crypt::decryptString($encrypted), true) ?: [];
    }

    /**
     * Cek & konsumsi (sekali pakai) recovery code milik user. Mengembalikan
     * true jika valid, dan langsung menghapus kode tersebut dari daftar.
     */
    public function consumeRecoveryCode(User $user, string $code): bool
    {
        $codes = $this->decryptRecoveryCodes($user->two_factor_recovery_codes);
        $normalized = Str::upper(trim($code));

        if (! in_array($normalized, $codes, true)) {
            return false;
        }

        $remaining = array_values(array_diff($codes, [$normalized]));
        $user->two_factor_recovery_codes = $this->encryptRecoveryCodes($remaining);
        $user->save();

        return true;
    }
}
