<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\PasswordService;
use Database\Seeders\UserAdminRomadan;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AdminSeederTest extends TestCase
{
    /**
     * Tabel dibuat manual (tanpa RefreshDatabase) karena migrasi penuh tidak
     * bisa berjalan di SQLite — lihat catatan di AuthAccessTest.
     */
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

        Schema::create('roles', function ($table) {
            $table->id();
            $table->string('name');
            $table->string('guard_name');
            $table->timestamps();
        });

        Schema::create('model_has_roles', function ($table) {
            $table->unsignedBigInteger('role_id');
            $table->string('model_type');
            $table->unsignedBigInteger('model_id');
        });
    }

    /**
     * Jalankan seeder lalu ambil password yang dicetaknya: baris tepat setelah
     * label "Password awal" (db:seed masih mencetak baris status "DONE" sesudahnya).
     */
    private function seedAndCapturePassword(): string
    {
        Artisan::call('db:seed', ['--class' => UserAdminRomadan::class]);

        $lines = array_map('rtrim', explode("\n", Artisan::output()));
        $labelIndex = null;
        foreach ($lines as $i => $line) {
            if (str_contains($line, 'Password awal')) {
                $labelIndex = $i;
            }
        }

        $this->assertNotNull($labelIndex, 'Seeder tidak mencetak password awal.');

        return trim($lines[$labelIndex + 1]);
    }

    public function test_seeded_admin_can_log_in_with_the_password_printed_once(): void
    {
        $printedPassword = $this->seedAndCapturePassword();

        $admin = User::where('email', 'admin@romadan.kemenkeu.go.id')->firstOrFail();

        $this->assertTrue($admin->hasRole('ADMINISTRATOR'));
        $this->assertTrue((new PasswordService)->verify($printedPassword, $admin->salt, $admin->password));
    }

    public function test_every_run_generates_a_different_password(): void
    {
        $first = $this->seedAndCapturePassword();

        User::query()->delete();
        $second = $this->seedAndCapturePassword();

        $this->assertNotSame($first, $second);
        $this->assertGreaterThanOrEqual(24, strlen($first));
    }

    public function test_seeder_is_idempotent(): void
    {
        Artisan::call('db:seed', ['--class' => UserAdminRomadan::class]);
        Artisan::call('db:seed', ['--class' => UserAdminRomadan::class]);

        $this->assertStringContainsString('sudah ada', Artisan::output());
        $this->assertSame(1, User::where('email', 'admin@romadan.kemenkeu.go.id')->count());
    }
}
