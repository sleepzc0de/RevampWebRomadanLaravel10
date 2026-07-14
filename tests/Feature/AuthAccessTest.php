<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AuthAccessTest extends TestCase
{
    private const LOGIN_PATH = '/bDBnMW5fY201X2IxcjBtNGQ0bl9rM21lbmszdQ==';

    /**
     * Test-test di sini sengaja tidak memakai RefreshDatabase (migrasi penuh
     * gagal di SQLite karena masalah versi spatie/laravel-permission yang
     * tidak terkait). Login page kini query tabel login_gambar untuk gambar
     * latar, jadi buat tabel itu saja secara manual agar route bisa di-hit.
     */
    protected function setUp(): void
    {
        parent::setUp();

        if (! Schema::hasTable('login_gambar')) {
            Schema::create('login_gambar', function ($table) {
                $table->id();
                $table->string('nama_gambar');
                $table->string('image');
                $table->timestamps();
            });
        }
    }

    public function test_login_page_renders(): void
    {
        $response = $this->get(self::LOGIN_PATH);

        $response->assertOk();
    }

    public function test_backend_requires_authentication(): void
    {
        $response = $this->get(route('home'));

        $response->assertRedirect(route('login'));
    }

    public function test_backups_require_authentication(): void
    {
        $response = $this->get(route('backups.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_login_validation_rejects_empty_payload(): void
    {
        $response = $this->from(self::LOGIN_PATH)->post(self::LOGIN_PATH, []);

        $response->assertSessionHasErrors(['email', 'password', 'captcha', 'captcha_token']);
    }
}
