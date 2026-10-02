<?php

namespace Tests\Unit;

use App\Models\backend\MenuInformasiPublik\PeraturanModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * Filter publik Peraturan: ATAU dalam satu kelompok centang, DAN antar kelompok
 * dan kata kunci. Dulu memakai orWhereHas sehingga menambah filter justru
 * memperluas hasil.
 */
class PeraturanFilterTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config(['activitylog.enabled' => false]);

        Schema::create('ref_kategori', function ($t) {
            $t->id('id_kategori');
            $t->string('nama_kategori');
        });
        Schema::create('ref_jenis_peraturan', function ($t) {
            $t->id('id_jenis_peraturan');
            $t->string('nama_jenis_peraturan');
        });
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
            $t->string('slug')->nullable();
            $t->timestamps();
        });

        DB::table('ref_kategori')->insert([['nama_kategori' => 'BMN'], ['nama_kategori' => 'Pengadaan']]);
        DB::table('ref_jenis_peraturan')->insert([['nama_jenis_peraturan' => 'Perpres'], ['nama_jenis_peraturan' => 'PMK']]);

        $this->peraturan('Aset Negara', kategori: 1, jenis: 1);       // BMN + Perpres
        $this->peraturan('Aset Daerah', kategori: 2, jenis: 1);       // Pengadaan + Perpres
        $this->peraturan('Gedung Negara', kategori: 1, jenis: 2);     // BMN + PMK
    }

    private function peraturan(string $judul, int $kategori, int $jenis): void
    {
        PeraturanModel::create([
            'nomor_peraturan' => 'NO-'.$judul,
            'judul_peraturan' => $judul,
            'file' => 'x.pdf',
            'kategori' => $kategori,
            'jenis_peraturan' => $jenis,
            'slug' => str($judul)->slug(),
        ]);
    }

    private function judul(?string $cari = null, array $kategori = [], array $jenis = []): array
    {
        return PeraturanModel::filterPublik($cari, $kategori, $jenis)
            ->orderBy('judul_peraturan')
            ->pluck('judul_peraturan')
            ->all();
    }

    public function test_no_filter_returns_everything(): void
    {
        $this->assertSame(['Aset Daerah', 'Aset Negara', 'Gedung Negara'], $this->judul());
    }

    public function test_several_checks_in_one_group_are_or(): void
    {
        $this->assertSame(['Aset Daerah', 'Aset Negara', 'Gedung Negara'], $this->judul(kategori: ['BMN', 'Pengadaan']));
        $this->assertSame(['Aset Negara', 'Gedung Negara'], $this->judul(kategori: ['BMN']));
    }

    public function test_different_groups_are_and_not_a_union(): void
    {
        // BMN ∩ Perpres → hanya "Aset Negara" (union lama mengembalikan ketiganya)
        $this->assertSame(['Aset Negara'], $this->judul(kategori: ['BMN'], jenis: ['Perpres']));
    }

    public function test_keyword_narrows_the_filtered_results(): void
    {
        // "Aset" ∩ Pengadaan → "Aset Daerah" saja
        $this->assertSame(['Aset Daerah'], $this->judul(cari: 'Aset', kategori: ['Pengadaan']));
    }

    public function test_keyword_also_matches_category_and_type_names(): void
    {
        $this->assertSame(['Gedung Negara'], $this->judul(cari: 'PMK'));
    }
}
