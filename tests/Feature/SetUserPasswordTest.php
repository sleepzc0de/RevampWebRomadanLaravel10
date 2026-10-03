<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\PasswordService;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class SetUserPasswordTest extends TestCase
{
    private const STRONG = 'Sandi-Baru-Yang-Kuat-2026!';

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'hashing.pepper' => 'test-pepper-value',
            'activitylog.enabled' => false, // tabel activity_log tidak dibuat di tes ini
        ]);

        Schema::create('users', function ($table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('username')->unique();
            $table->string('salt', 64);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    private function makeUser(string $password = 'Sandi-Lama-2026!'): User
    {
        $service = new PasswordService;
        $salt = $service->generateSalt();

        return User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'username' => 'admin',
            'password' => $service->hash($password, $salt),
            'salt' => $salt,
        ]);
    }

    public function test_it_changes_the_password_using_the_app_hashing_scheme(): void
    {
        $user = $this->makeUser();

        $this->artisan('user:set-password', ['email' => 'admin@example.com'])
            ->expectsQuestion('Password baru', self::STRONG)
            ->expectsQuestion('Ulangi password baru', self::STRONG)
            ->expectsOutputToContain('berhasil diganti')
            ->assertSuccessful();

        $fresh = $user->fresh();
        $service = new PasswordService;

        $this->assertTrue($service->verify(self::STRONG, $fresh->salt, $fresh->password));
        $this->assertFalse($service->verify('Sandi-Lama-2026!', $fresh->salt, $fresh->password));
        $this->assertNotSame($user->salt, $fresh->salt, 'salt harus diganti bersama password');
    }

    public function test_a_mismatched_confirmation_changes_nothing(): void
    {
        $user = $this->makeUser();
        $before = $user->password;

        $this->artisan('user:set-password', ['email' => 'admin@example.com'])
            ->expectsQuestion('Password baru', self::STRONG)
            ->expectsQuestion('Ulangi password baru', 'Beda-Sama-Sekali-2026!')
            ->assertFailed();

        $this->assertSame($before, $user->fresh()->password);
    }

    public function test_a_weak_password_is_refused(): void
    {
        $user = $this->makeUser();
        $before = $user->password;

        $this->artisan('user:set-password', ['email' => 'admin@example.com'])
            ->expectsQuestion('Password baru', 'pendek')
            ->expectsQuestion('Ulangi password baru', 'pendek')
            ->assertFailed();

        $this->assertSame($before, $user->fresh()->password);
    }

    public function test_an_unknown_email_is_reported(): void
    {
        $this->artisan('user:set-password', ['email' => 'tidak-ada@example.com'])
            ->expectsOutputToContain('tidak ditemukan')
            ->assertFailed();
    }
}
