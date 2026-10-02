<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Pastikan slug yang AKAN diturunkan dari sebuah judul belum dipakai baris lain.
 *
 * Kolom `slug` di beberapa tabel (peraturan, kegiatan, publikasi) berindeks
 * unik di database, tetapi nilainya dibuat dari judul lewat Str::slug(). Dua
 * judul yang berbeda ("Aset Negara!" vs "Aset Negara") bisa menghasilkan slug
 * yang sama; tanpa pengecekan ini penyimpanan gagal dengan error database dan
 * pengguna hanya melihat "Gagal Disimpan" tanpa tahu sebabnya. Judul yang
 * seluruhnya simbol ("!!!") menghasilkan slug kosong — ditolak juga.
 *
 * Baris yang sudah di-soft-delete tetap dihitung karena indeks unik database
 * juga menghitungnya.
 */
class UniqueSlug implements ValidationRule
{
    /**
     * @param  string  $table  tabel yang punya kolom slug
     * @param  int|string|null  $ignoreId  id baris yang sedang diedit (boleh memakai slug-nya sendiri)
     * @param  bool  $stripTags  samakan dengan controller: true bila controller menjalankan strip_tags() sebelum Str::slug()
     */
    public function __construct(
        private readonly string $table,
        private readonly int|string|null $ignoreId = null,
        private readonly bool $stripTags = true,
        private readonly string $column = 'slug',
        private readonly string $idColumn = 'id',
    ) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $text = (string) $value;
        $slug = Str::slug($this->stripTags ? strip_tags($text) : $text);

        if ($slug === '') {
            $fail('Judul harus memuat minimal satu huruf atau angka.');

            return;
        }

        $taken = DB::table($this->table)
            ->where($this->column, $slug)
            ->when($this->ignoreId !== null, fn ($q) => $q->where($this->idColumn, '!=', $this->ignoreId))
            ->exists();

        if ($taken) {
            $fail("Judul ini menghasilkan alamat halaman (\"{$slug}\") yang sudah dipakai data lain. Ubah judulnya sedikit.");
        }
    }
}
