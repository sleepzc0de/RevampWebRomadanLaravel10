<?php

namespace Tests\Feature;

use App\Http\Middleware\CheckRole;
use App\Models\backend\MenuPublikasi\PublikasiModel;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * Regresi PublikasiController::update — tabel dibuat manual (tanpa
 * RefreshDatabase) karena migrasi penuh tidak bisa berjalan di SQLite.
 */
class PublikasiUpdateTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config(['activitylog.enabled' => false]);

        Schema::create('ref_kategori', fn ($t) => $t->id('id_kategori'));
        Schema::create('ref_tipe', fn ($t) => $t->id('id_tipe'));
        Schema::create('ref_status', function ($t) {
            $t->id();
            $t->string('nama_status');
        });

        Schema::create('publikasi', function ($t) {
            $t->id();
            $t->string('judul');
            $t->string('sub_judul')->nullable();
            $t->string('image')->nullable();
            $t->unsignedBigInteger('tipe')->nullable();
            $t->unsignedBigInteger('kategori')->nullable();
            $t->string('slug')->nullable();
            $t->longText('isi')->nullable();
            $t->string('penulis')->nullable();
            $t->string('pengedit')->nullable();
            $t->string('status')->nullable();
            $t->string('static_random_string')->nullable();
            $t->timestamp('backdate')->nullable();
            $t->string('file')->nullable();
            $t->unsignedInteger('views')->default(0);
            $t->string('embedded_media')->nullable();
            $t->timestamp('published_at')->nullable();
            $t->timestamps();
            $t->softDeletes();
        });

        Schema::create('publikasi_images', function ($t) {
            $t->id();
            $t->unsignedBigInteger('publikasi_id');
            $t->string('image_path');
            $t->boolean('is_primary')->default(false);
            $t->unsignedInteger('sort_order')->default(0);
            $t->timestamps();
        });

        Schema::create('publikasi_revisions', function ($t) {
            $t->id();
            $t->unsignedBigInteger('publikasi_id');
            $t->string('judul');
            $t->string('sub_judul')->nullable();
            $t->longText('isi');
            $t->string('kategori')->nullable();
            $t->string('tipe')->nullable();
            $t->string('status')->nullable();
            $t->string('image')->nullable();
            $t->string('embedded_media')->nullable();
            $t->string('revised_by')->nullable();
            $t->timestamp('created_at')->nullable();
        });

        DB::table('ref_kategori')->insert(['id_kategori' => 1]);
        DB::table('ref_tipe')->insert(['id_tipe' => 1]);
        foreach (['published', 'draft', 'scheduled'] as $status) {
            DB::table('ref_status')->insert(['nama_status' => $status]);
        }
    }

    private function makePublikasi(array $attributes = []): PublikasiModel
    {
        return PublikasiModel::create(array_merge([
            'judul' => 'Judul Lama',
            'sub_judul' => 'Sub judul',
            'kategori' => 1,
            'tipe' => 1,
            'isi' => 'Isi publikasi yang cukup panjang.',
            'status' => 'published',
            'slug' => 'judul-lama',
            'created_at' => Carbon::parse('2026-01-01 08:00:00'),
        ], $attributes));
    }

    private function addImages(PublikasiModel $publikasi, int $count): array
    {
        $images = [];
        for ($i = 1; $i <= $count; $i++) {
            $images[] = $publikasi->images()->create([
                'image_path' => "gambar-{$i}.jpg",
                'is_primary' => $i === 1,
                'sort_order' => $i,
            ]);
        }
        $publikasi->update(['image' => 'gambar-1.jpg']);

        return $images;
    }

    private function update(PublikasiModel $publikasi, array $overrides = [])
    {
        return $this->actingAs(new User(['id' => 1, 'name' => 'Redaktur Uji']))
            ->withoutMiddleware(CheckRole::class)
            ->put(route('publikasi.update', encrypt($publikasi->id)), array_merge([
                'judul' => 'Judul Baru',
                'sub_judul' => 'Sub judul baru',
                'kategori' => 1,
                'tipe' => 1,
                'status' => 'published',
                'isi' => 'Isi publikasi yang sudah disunting.',
                'created_at' => '2026-01-01 08:00:00',
            ], $overrides));
    }

    public function test_editing_an_already_published_article_keeps_its_publish_date(): void
    {
        $tayang = Carbon::parse('2026-03-15 09:30:00');
        $publikasi = $this->makePublikasi(['published_at' => $tayang]);

        $this->update($publikasi)->assertSessionHasNoErrors();

        $this->assertTrue($publikasi->fresh()->published_at->equalTo($tayang));
        $this->assertSame('Judul Baru', $publikasi->fresh()->judul);
    }

    public function test_publishing_a_scheduled_article_early_makes_it_go_live_now(): void
    {
        $publikasi = $this->makePublikasi([
            'status' => 'scheduled',
            'published_at' => now()->addDays(5),
        ]);

        $this->update($publikasi, ['status' => 'published'])->assertSessionHasNoErrors();

        $this->assertTrue($publikasi->fresh()->published_at->lessThanOrEqualTo(now()));
    }

    public function test_moving_a_scheduled_article_back_to_draft_cancels_the_schedule(): void
    {
        $publikasi = $this->makePublikasi([
            'status' => 'scheduled',
            'published_at' => now()->addDays(5),
        ]);

        $this->update($publikasi, ['status' => 'draft'])->assertSessionHasNoErrors();

        $this->assertNull($publikasi->fresh()->published_at);
    }

    public function test_removing_every_image_is_rejected_and_nothing_is_deleted(): void
    {
        $publikasi = $this->makePublikasi();
        $images = $this->addImages($publikasi, 2);

        $response = $this->update($publikasi, [
            'delete_images' => [$images[0]->id, $images[1]->id],
        ]);

        $response->assertSessionHasErrors('delete_images');
        $this->assertSame(2, $publikasi->images()->count());
        $this->assertSame('Judul Lama', $publikasi->fresh()->judul);
    }

    public function test_deleting_the_primary_image_promotes_another_one(): void
    {
        $publikasi = $this->makePublikasi();
        $images = $this->addImages($publikasi, 2);

        $this->update($publikasi, ['delete_images' => [$images[0]->id]])->assertSessionHasNoErrors();

        $remaining = $publikasi->images()->get();
        $this->assertCount(1, $remaining);
        $this->assertTrue((bool) $remaining->first()->is_primary);
        $this->assertSame('gambar-2.jpg', $publikasi->fresh()->image);
    }

    public function test_legacy_article_without_image_rows_can_still_be_edited(): void
    {
        $publikasi = $this->makePublikasi();

        $this->update($publikasi)->assertSessionHasNoErrors();

        $this->assertSame('Judul Baru', $publikasi->fresh()->judul);
    }
}
