<?php

namespace App\Services;

use Illuminate\Support\Facades\Hash;
use RuntimeException;

class PasswordService
{
    private const HASH_ALGO = 'sha256';

    public function generateSalt(): string
    {
        return bin2hex(random_bytes(32));
    }

    public function hash(string $password, string $salt): string
    {
        return Hash::make($this->pepperedHash($password, $salt));
    }

    public function verify(string $password, string $salt, string $hashedPassword): bool
    {
        return Hash::check($this->pepperedHash($password, $salt), $hashedPassword);
    }

    /**
     * Habiskan waktu yang sama dengan verify() untuk akun yang TIDAK ada.
     *
     * Tanpa ini, login dengan email tak terdaftar kembali jauh lebih cepat
     * (tidak ada bcrypt) daripada email terdaftar + password salah, sehingga
     * penyerang bisa menebak email mana yang valid dari waktu respons.
     */
    public function equalizeTiming(string $password): void
    {
        $this->hash($password, str_repeat('0', 64));
    }

    private function pepperedHash(string $password, string $salt): string
    {
        return hash_hmac(self::HASH_ALGO, $password.$salt, $this->pepper());
    }

    private function pepper(): string
    {
        $pepper = config('hashing.pepper');

        if (empty($pepper)) {
            throw new RuntimeException(
                'PASSWORD_PEPPER belum dikonfigurasi. Set nilai base64 pepper pada .env.'
            );
        }

        return $pepper;
    }
}
