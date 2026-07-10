<?php

namespace Tests\Unit;

use App\Services\CaptchaService;
use Tests\TestCase;

class CaptchaServiceTest extends TestCase
{
    private CaptchaService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new CaptchaService;
    }

    public function test_create_returns_question_and_token(): void
    {
        $captcha = $this->service->createCaptcha();

        $this->assertArrayHasKey('question', $captcha);
        $this->assertArrayHasKey('token', $captcha);
        $this->assertNotEmpty(session('captcha_data'));
    }

    public function test_correct_answer_validates(): void
    {
        $this->service->createCaptcha();
        $data = session('captcha_data');

        $this->assertTrue($this->service->validateCaptcha($data['answer'], $data['token']));
    }

    public function test_wrong_answer_fails(): void
    {
        $this->service->createCaptcha();
        $data = session('captcha_data');

        $this->assertFalse($this->service->validateCaptcha('999999', $data['token']));
    }

    public function test_wrong_token_fails(): void
    {
        $this->service->createCaptcha();
        $data = session('captcha_data');

        $this->assertFalse($this->service->validateCaptcha($data['answer'], 'token-palsu'));
    }

    public function test_token_is_single_use(): void
    {
        $this->service->createCaptcha();
        $data = session('captcha_data');

        $this->assertTrue($this->service->validateCaptcha($data['answer'], $data['token']));
        // Setelah sukses, data captcha dibersihkan → percobaan kedua gagal
        $this->assertFalse($this->service->validateCaptcha($data['answer'], $data['token']));
    }
}
