<?php

namespace Tests\Feature;

use App\Http\Middleware\CheckRole;
use App\Models\backend\MenuInformasiPublik\PeraturanModel;
use App\Models\User;
use App\Rules\UniqueSlug;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

/**
 * Kolom slug berindeks unik di database tetapi diturunkan dari judul. Dua judul
 * berbeda bisa menghasilkan slug sama; sebelumnya itu berujung "Gagal Disimpan"
 * tanpa penjelasan. Sekarang harus menjadi error validasi pada kolom judul.
 */
class SlugValidationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config(['activitylog.enabled' => false]);

        Schema::create('peraturan', function ($t) {
            $t->id();
            $t->string('nomor_peraturan')->nullable();
            $t->string('judul_peraturan');
            $t->string('file')->nullable();
            $t->unsignedBigInteger('kategori')->nullable();
            $t->unsignedBigInteger('jenis_peraturan')->nullable();
            $t->date('tanggal_penetapan')->nullable();
            $t->date('tanggal_berlaku')->nullable();
            $t->unsignedBigInteger('status_peraturan')->nullable();
            $t->string('slug')->unique();
            $t->timestamps();
        });

        Schema::create('kegiatan', function ($t) {
            $t->id();
            $t->string('judul');
            $t->string('slug')->unique();
            $t->timestamps();
        });

        Schema::create('publikasi', function ($t) {
            $t->id();
            $t->string('judul');
            $t->string('slug')->unique();
            $t->timestamp('deleted_at')->nullable();
            $t->timestamps();
        });
    }

    private function admin(): static
    {
        return $this->actingAs(new User(['id' => 1, 'name' => 'Admin Uji']))->withoutMiddleware(CheckRole::class);
    }

    private function passes(UniqueSlug $rule, string $judul): bool
    {
        return Validator::make(['judul' => $judul], ['judul' => [$rule]])->passes();
    }

    // ---- aturan UniqueSlug sendiri ------------------------------------------------

    public function test_a_different_title_that_yields_the_same_slug_is_rejected(): void
    {
        DB::table('peraturan')->insert(['judul_peraturan' => 'Aset Negara', 'slug' => 'aset-negara']);

        $this->assertFalse($this->passes(new UniqueSlug('peraturan'), 'Aset Negara!'));
        $this->assertFalse($this->passes(new UniqueSlug('peraturan'), 'aset   NEGARA'));
        $this->assertTrue($this->passes(new UniqueSlug('peraturan'), 'Aset Daerah'));
    }

    public function test_a_row_may_keep_its_own_slug_when_edited(): void
    {
        $id = DB::table('peraturan')->insertGetId(['judul_peraturan' => 'Aset Negara', 'slug' => 'aset-negara']);

        $this->assertTrue($this->passes(new UniqueSlug('peraturan', $id), 'Aset Negara'));
        $this->assertFalse($this->passes(new UniqueSlug('peraturan', $id + 99), 'Aset Negara'));
    }

    public function test_a_title_made_only_of_symbols_is_rejected(): void
    {
        $this->assertFalse($this->passes(new UniqueSlug('peraturan'), '!!! ???'));
    }

    public function test_soft_deleted_rows_still_count_because_the_database_index_counts_them(): void
    {
        DB::table('publikasi')->insert(['judul' => 'Berita Lama', 'slug' => 'berita-lama', 'deleted_at' => now()]);

        $this->assertFalse($this->passes(new UniqueSlug('publikasi'), 'Berita Lama'));
    }

    public function test_strip_tags_option_matches_what_the_controller_does(): void
    {
        DB::table('peraturan')->insert(['judul_peraturan' => 'Aset', 'slug' => 'aset']);

        $this->assertFalse($this->passes(new UniqueSlug('peraturan'), '<b>Aset</b>'), 'controller menghapus tag lebih dulu → slug "aset"');
        $this->assertTrue($this->passes(new UniqueSlug('peraturan', stripTags: false), '<b>Aset</b>'), 'tanpa strip_tags slug-nya "b-aset-b"');
    }

    // ---- terpasang di controller --------------------------------------------------

    public function test_peraturan_store_reports_the_slug_clash_on_the_title_field(): void
    {
        DB::table('peraturan')->insert(['judul_peraturan' => 'Aset Negara', 'slug' => 'aset-negara']);

        $response = $this->admin()->post(route('peraturan.store'), [
            'nomor_peraturan' => 'PMK-1',
            'judul_peraturan' => 'Aset Negara!',
            'kategori' => 1,
            'jenis_peraturan' => 1,
            'tanggal_penetapan' => '2026-01-01',
            'tanggal_berlaku' => '2026-01-02',
        ]);

        $response->assertSessionHasErrors('judul_peraturan');
        $response->assertSessionMissing('failed');
        $this->assertSame(1, DB::table('peraturan')->count());
    }

    public function test_peraturan_update_allows_own_title_but_rejects_another_rows_slug(): void
    {
        DB::table('peraturan')->insert(['judul_peraturan' => 'Aset Daerah', 'slug' => 'aset-daerah']);
        $mine = PeraturanModel::create([
            'nomor_peraturan' => 'PMK-2',
            'judul_peraturan' => 'Aset Negara',
            'file' => 'x.pdf',
            'kategori' => 1,
            'jenis_peraturan' => 1,
            'slug' => 'aset-negara',
        ]);

        $payload = [
            'nomor_peraturan' => 'PMK-2',
            'kategori' => 1,
            'jenis_peraturan' => 1,
            'tanggal_penetapan' => '2026-01-01',
            'tanggal_berlaku' => '2026-01-02',
        ];

        $this->admin()
            ->put(route('peraturan.update', encrypt($mine->id)), $payload + ['judul_peraturan' => 'Aset Negara'])
            ->assertSessionHasNoErrors()
            ->assertSessionHas('success');

        $this->admin()
            ->put(route('peraturan.update', encrypt($mine->id)), $payload + ['judul_peraturan' => 'Aset Daerah'])
            ->assertSessionHasErrors('judul_peraturan');

        $this->assertSame('aset-negara', $mine->fresh()->slug);
    }

    public function test_kegiatan_store_reports_the_slug_clash(): void
    {
        DB::table('kegiatan')->insert(['judul' => 'Rapat Kerja', 'slug' => 'rapat-kerja']);

        $this->admin()
            ->post(route('kegiatan.store'), ['judul' => 'Rapat Kerja!'])
            ->assertSessionHasErrors('judul');
    }

    public function test_publikasi_store_reports_the_slug_clash(): void
    {
        DB::table('publikasi')->insert(['judul' => 'Berita Baru', 'slug' => 'berita-baru']);

        $this->admin()
            ->post(route('publikasi.store'), ['judul' => 'Berita Baru?'])
            ->assertSessionHasErrors('judul');
    }
}
