<?php

namespace Tests\Feature;

use App\Http\Middleware\CheckRole;
use App\Models\User;
use App\Services\PasswordService;
use App\Services\TwoFactorService;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use PragmaRX\Google2FA\Google2FA;
use Tests\TestCase;

class AuthHardeningTest extends TestCase
{
    private const LOGIN_PATH = '/bDBnMW5fY201X2IxcjBtNGQ0bl9rM21lbmszdQ==';

    protected function setUp(): void
    {
        parent::setUp();

        config(['hashing.pepper' => 'test-pepper-value']);

        // Route probe: ikut grup middleware "web" (termasuk CheckIdleTimeout).
        Route::middleware('web')->get('/__idle-probe', fn () => 'ok')->name('idle-probe');
    }

    private function loggedIn(): static
    {
        return $this->actingAs(new User(['id' => 1, 'name' => 'Admin Uji']));
    }

    // ---- Idle timeout ------------------------------------------------------

    public function test_a_normal_request_refreshes_the_idle_clock(): void
    {
        $old = time() - 120;

        $this->loggedIn()->withSession(['last_activity' => $old])->get('/__idle-probe');

        $this->assertGreaterThan($old, session('last_activity'));
    }

    public function test_the_monitor_polling_endpoint_does_not_extend_the_session(): void
    {
        $old = time() - 120;

        $this->loggedIn()
            ->withoutMiddleware(CheckRole::class)
            ->withSession(['last_activity' => $old])
            ->getJson(route('system-monitor.data'))
            ->assertOk();

        $this->assertSame($old, session('last_activity'));
    }

    public function test_an_expired_session_gets_401_json_for_ajax_requests(): void
    {
        $expired = time() - (config('session.idle_timeout') * 60 + 10);

        $this->loggedIn()
            ->withSession(['last_activity' => $expired])
            ->getJson('/__idle-probe')
            ->assertStatus(401);
    }

    public function test_an_expired_session_redirects_browsers_to_login(): void
    {
        $expired = time() - (config('session.idle_timeout') * 60 + 10);

        $this->loggedIn()
            ->withSession(['last_activity' => $expired])
            ->get('/__idle-probe')
            ->assertRedirect(route('login'));
    }

    // ---- TOTP sekali pakai ---------------------------------------------------

    public function test_a_totp_code_can_only_be_used_once(): void
    {
        $service = new TwoFactorService;
        $secret = $service->generateSecretKey();
        $user = new User(['id' => 77]);
        $user->two_factor_secret = $service->encryptSecret($secret);

        $code = (new Google2FA)->getCurrentOtp($secret);

        $this->assertTrue($service->verifyCodeOnce($user, $code));
        $this->assertFalse($service->verifyCodeOnce($user, $code), 'kode yang sama harus ditolak saat dipakai ulang');
    }

    public function test_a_wrong_totp_code_does_not_burn_the_valid_one(): void
    {
        $service = new TwoFactorService;
        $secret = $service->generateSecretKey();
        $user = new User(['id' => 78]);
        $user->two_factor_secret = $service->encryptSecret($secret);

        $this->assertFalse($service->verifyCodeOnce($user, '000000'));
        $this->assertTrue($service->verifyCodeOnce($user, (new Google2FA)->getCurrentOtp($secret)));
    }

    // ---- Waktu respons login -------------------------------------------------

    public function test_login_with_an_unknown_email_still_spends_a_password_hash(): void
    {
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

        $this->partialMock(PasswordService::class, function ($mock) {
            $mock->shouldReceive('equalizeTiming')->once();
        });

        $response = $this->withSession([
            'captcha_data' => ['token' => 'tok', 'answer' => '5', 'created_at' => time(), 'attempts' => 0],
        ])->from(self::LOGIN_PATH)->post(self::LOGIN_PATH, [
            'email' => 'tidak-ada@example.com',
            'password' => 'apa-saja',
            'captcha' => '5',
            'captcha_token' => 'tok',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_equalize_timing_runs_without_error(): void
    {
        (new PasswordService)->equalizeTiming('apa-saja');

        $this->addToAssertionCount(1);
    }
}
