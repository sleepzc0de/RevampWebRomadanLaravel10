<?php

namespace Tests\Feature;

use App\Http\Middleware\CheckRole;
use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * Regresi: controller CRUD membungkus validate() di dalam try/catch (Exception),
 * sehingga ValidationException ikut tertelan dan pengguna hanya melihat flash
 * "Gagal" tanpa tahu field mana yang salah. Error validasi harus sampai ke
 * session ($errors) supaya form bisa menampilkannya.
 */
class ValidationFeedbackTest extends TestCase
{
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

    public function test_invalid_store_returns_field_errors_instead_of_a_generic_failure(): void
    {
        $response = $this->actingAs(new User(['id' => 1, 'name' => 'Admin']))
            ->withoutMiddleware(CheckRole::class)
            ->from(route('loggambar.create'))
            ->post(route('loggambar.store'), []);

        $response->assertRedirect(route('loggambar.create'));
        $response->assertSessionHasErrors(['nama_gambar', 'image']);
        $response->assertSessionMissing('failed');
    }

    public function test_session_notif_partial_lists_validation_errors(): void
    {
        $html = view('layouts.webromadan_backend.session_notif')
            ->withErrors(['nama_gambar' => 'Nama gambar wajib diisi.'])
            ->render();

        $this->assertStringContainsString('Periksa kembali isian Anda', $html);
        $this->assertStringContainsString('Nama gambar wajib diisi.', $html);
    }
}
