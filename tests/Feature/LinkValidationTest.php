<?php

namespace Tests\Feature;

use App\Http\Middleware\CheckRole;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * Tautan yang diinput admin tampil sebagai href di SEMUA halaman publik
 * (footer), jadi skema berbahaya & URL protokol-relatif harus ditolak.
 */
class LinkValidationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config(['activitylog.enabled' => false]);

        Schema::create('footer_links', function ($t) {
            $t->id();
            $t->string('label');
            $t->string('url');
            $t->integer('sort_order')->default(0);
            $t->boolean('is_active')->default(true);
            $t->timestamps();
        });

        Schema::create('medsos', function ($t) {
            $t->id();
            $t->string('nama_medsos');
            $t->string('link_medsos', 1000);
            $t->string('logo_medsos');
            $t->timestamps();
        });
    }

    private function admin(): static
    {
        return $this->actingAs(new User(['id' => 1, 'name' => 'Admin Uji']))->withoutMiddleware(CheckRole::class);
    }

    public function test_footer_link_rejects_protocol_relative_and_dangerous_urls(): void
    {
        foreach (['//evil.example/phish', '/\\evil.example', 'javascript:alert(1)', 'data:text/html,x'] as $bad) {
            $this->admin()
                ->post(route('footer-link.store'), ['label' => 'Tautan', 'url' => $bad])
                ->assertSessionHasErrors('url');
        }

        $this->assertSame(0, DB::table('footer_links')->count());
    }

    public function test_footer_link_accepts_internal_paths_and_https_urls(): void
    {
        foreach (['/profile/sejarah', 'https://www.kemenkeu.go.id/'] as $good) {
            $this->admin()
                ->post(route('footer-link.store'), ['label' => 'Tautan', 'url' => $good])
                ->assertSessionHasNoErrors();
        }

        $this->assertSame(2, DB::table('footer_links')->count());
    }

    public function test_medsos_link_must_be_a_real_url(): void
    {
        $this->admin()
            ->post(route('medsos.store'), ['nama_medsos' => 'X', 'link_medsos' => 'javascript:alert(1)', 'logo_medsos' => 'fa-x'])
            ->assertSessionHasErrors('link_medsos');

        $this->assertSame(0, DB::table('medsos')->count());
    }
}
