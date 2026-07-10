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
