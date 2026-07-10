<?php

namespace Tests\Feature;

use Tests\TestCase;

class AuthAccessTest extends TestCase
{
    private const LOGIN_PATH = '/bDBnMW5fY201X2IxcjBtNGQ0bl9rM21lbmszdQ==';

    public function test_login_page_renders(): void
    {
        $response = $this->get(self::LOGIN_PATH);

        $response->assertOk();
    }

    public function test_backend_requires_authentication(): void
    {
        $response = $this->get('/backend/romadan-interface/dashboard');

        $response->assertRedirect(route('login'));
    }

    public function test_backups_require_authentication(): void
    {
        $response = $this->get('/backups');

        $response->assertRedirect(route('login'));
    }

    public function test_login_validation_rejects_empty_payload(): void
    {
        $response = $this->from(self::LOGIN_PATH)->post(self::LOGIN_PATH, []);

        $response->assertSessionHasErrors(['email', 'password', 'captcha', 'captcha_token']);
    }
}
