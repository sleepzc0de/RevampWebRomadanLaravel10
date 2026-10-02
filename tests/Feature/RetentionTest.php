<?php

namespace Tests\Feature;

use App\Http\Controllers\MenuVisitor\VisitorController;
use App\Http\Middleware\CheckRole;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use ReflectionMethod;
use Tests\TestCase;

/**
 * Pembersihan log pengunjung & audit: validasi batas, jejak audit, prune
 * terjadwal, dan tren harian tanpa memuat semua baris ke memori.
 */
class RetentionTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('visitors', function ($t) {
            $t->id();
            $t->string('ip_address')->nullable();
            $t->string('user_agent', 500)->nullable();
            $t->string('browser')->nullable();
            $t->string('platform')->nullable();
            $t->string('device_type')->nullable();
            $t->string('url', 500)->nullable();
            $t->string('route_name')->nullable();
            $t->string('referrer', 500)->nullable();
            $t->string('session_id')->nullable();
            $t->boolean('is_bot')->default(false);
            $t->timestamps();
        });

        Schema::create('activity_log', function ($t) {
            $t->id();
            $t->string('log_name')->nullable();
            $t->text('description');
            $t->nullableMorphs('subject');
            $t->string('event')->nullable();
            $t->nullableMorphs('causer');
            $t->json('properties')->nullable();
            $t->uuid('batch_uuid')->nullable();
            $t->timestamps();
        });
    }

    private function visitorRows(int $count, string $createdAt, bool $bot = false): void
    {
        foreach (array_chunk(range(1, $count), 500) as $chunk) {
            DB::table('visitors')->insert(array_map(fn () => [
                'ip_address' => '10.0.0.1',
                'url' => 'publikasi',
                'is_bot' => $bot,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ], $chunk));
        }
    }

    private function admin(): static
    {
        return $this->actingAs(new User(['id' => 1, 'name' => 'Admin Uji']))->withoutMiddleware(CheckRole::class);
    }

    // ---- visitors:prune --------------------------------------------------------

    public function test_prune_removes_old_rows_in_batches_and_keeps_recent_ones(): void
    {
        $this->visitorRows(5200, now()->subDays(120)->toDateTimeString()); // > satu batch (5000)
        $this->visitorRows(3, now()->subDays(2)->toDateTimeString());

        $this->assertSame(0, Artisan::call('visitors:prune', ['--days' => 90]));

        $this->assertSame(3, DB::table('visitors')->count());
    }

    public function test_prune_refuses_a_retention_shorter_than_a_week(): void
    {
        $this->visitorRows(4, now()->subDays(3)->toDateTimeString());

        $this->assertNotSame(0, Artisan::call('visitors:prune', ['--days' => 1]));

        $this->assertSame(4, DB::table('visitors')->count());
    }

    // ---- tombol "bersihkan" di backend ------------------------------------------

    public function test_visitor_clean_rejects_days_that_would_wipe_everything(): void
    {
        $this->visitorRows(3, now()->subDays(40)->toDateTimeString());

        foreach ([0, -5, 3] as $bad) {
            $this->admin()->post(route('visitors.clean'), ['days' => $bad])->assertSessionHasErrors('days');
        }

        $this->assertSame(3, DB::table('visitors')->count());
    }

    public function test_audit_log_clean_rejects_days_that_would_wipe_everything(): void
    {
        DB::table('activity_log')->insert(['log_name' => 'x', 'description' => 'lama', 'created_at' => now()->subDays(40), 'updated_at' => now()]);

        foreach ([0, -1, 10] as $bad) {
            $this->admin()->post(route('activity-log.clean'), ['days' => $bad])->assertSessionHasErrors('days');
        }

        $this->assertSame(1, DB::table('activity_log')->count());
    }

    public function test_audit_log_clean_leaves_an_audit_entry_about_itself(): void
    {
        DB::table('activity_log')->insert([
            ['log_name' => 'x', 'description' => 'sangat lama', 'created_at' => now()->subDays(500), 'updated_at' => now()],
            ['log_name' => 'x', 'description' => 'baru', 'created_at' => now()->subDays(5), 'updated_at' => now()],
        ]);

        $this->admin()->post(route('activity-log.clean'))->assertSessionHasNoErrors();

        $this->assertDatabaseMissing('activity_log', ['description' => 'sangat lama']);
        $this->assertDatabaseHas('activity_log', ['description' => 'baru']);
        $this->assertSame(1, DB::table('activity_log')->where('log_name', 'audit')->count());
    }

    // ---- tren harian ----------------------------------------------------------------

    public function test_daily_trend_counts_humans_per_day_without_loading_rows(): void
    {
        $this->visitorRows(2, now()->toDateTimeString());                          // hari ini (manusia)
        $this->visitorRows(1, now()->toDateTimeString(), bot: true);              // hari ini (bot → tidak dihitung)
        $this->visitorRows(1, now()->subDay()->toDateTimeString());               // kemarin
        $this->visitorRows(5, now()->subDays(30)->toDateTimeString());            // di luar jendela 7 hari

        $trend = (new ReflectionMethod(VisitorController::class, 'dailyTrend'))->invoke(new VisitorController);

        $this->assertCount(7, $trend['labels']);
        $this->assertSame([0, 0, 0, 0, 0, 1, 2], $trend['data']);
    }
}
