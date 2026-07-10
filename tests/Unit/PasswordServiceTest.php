<?php

namespace Tests\Unit;

use App\Services\PasswordService;
use Illuminate\Support\Facades\Hash;
use RuntimeException;
use Tests\TestCase;

class PasswordServiceTest extends TestCase
{
    private PasswordService $service;

    protected function setUp(): void
    {
        parent::setUp();
        config(['hashing.pepper' => 'test-pepper-value']);
        $this->service = new PasswordService;
    }

    public function test_hash_then_verify_round_trip(): void
    {
        $salt = $this->service->generateSalt();
        $hash = $this->service->hash('rahasia123', $salt);

        $this->assertTrue($this->service->verify('rahasia123', $salt, $hash));
    }

    public function test_wrong_password_is_rejected(): void
    {
        $salt = $this->service->generateSalt();
        $hash = $this->service->hash('rahasia123', $salt);

        $this->assertFalse($this->service->verify('password-salah', $salt, $hash));
    }

    public function test_same_password_different_salt_produces_different_hash(): void
    {
        $hashA = $this->service->hash('rahasia123', $this->service->generateSalt());
        $hashB = $this->service->hash('rahasia123', $this->service->generateSalt());

        $this->assertNotSame($hashA, $hashB);
    }

    public function test_generated_salt_is_64_hex_chars(): void
    {
        $salt = $this->service->generateSalt();

        $this->assertSame(64, strlen($salt));
        $this->assertMatchesRegularExpression('/^[0-9a-f]{64}$/', $salt);
    }

    public function test_verify_is_compatible_with_legacy_inline_scheme(): void
    {
        // Hash yang dibuat dengan skema lama (HMAC-SHA256 + bcrypt) harus
        // tetap lolos verify() agar user existing bisa login tanpa reset.
        $salt = $this->service->generateSalt();
        $legacyHash = Hash::make(hash_hmac('sha256', 'rahasia123'.$salt, 'test-pepper-value'));

        $this->assertTrue($this->service->verify('rahasia123', $salt, $legacyHash));
    }

    public function test_missing_pepper_throws(): void
    {
        config(['hashing.pepper' => null]);

        $this->expectException(RuntimeException::class);
        $this->service->hash('rahasia123', $this->service->generateSalt());
    }
}
