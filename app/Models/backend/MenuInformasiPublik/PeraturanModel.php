<?php

namespace App\Models\backend\MenuInformasiPublik;

use App\Models\backend\MenuReferensi\RefJenisPeraturan;
use App\Models\backend\MenuReferensi\RefPeraturanStatus;
use App\Models\backend\RefKategori;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class PeraturanModel extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'peraturan';

    protected $guarded = [];

    protected $fillable = ['nomor_peraturan', 'judul_peraturan', 'file', 'kategori', 'jenis_peraturan', 'tanggal_penetapan', 'tanggal_berlaku', 'status_peraturan', 'slug'];

    protected $hidden = [
        'created_at',
        'updated_at',
        'id',
    ];

    public function kategori()
    {
        return $this->belongsTo(RefKategori::class, 'kategori', 'id_kategori')->withDefault(['nama_jenis_peraturan' => 'KATEGORI BELUM DIPILIH']);
    }

    public function data_jenis_peraturan()
    {
        return $this->belongsTo(RefJenisPeraturan::class, 'jenis_peraturan', 'id_jenis_peraturan')->withDefault(['nama_jenis_peraturan' => 'JENIS PERATURAN BELUM DIPILIH']);
    }

    public function data_status_peraturan()
    {
        return $this->belongsTo(RefPeraturanStatus::class, 'status_peraturan', 'id_ref_peraturan_status')->withDefault(['nama_peraturan_status' => 'STATUS BELUM DIISI']);
    }

    /**
     * Pencarian + filter checkbox untuk halaman publik.
     *
     * Beberapa centang dalam SATU kelompok (mis. dua kategori) bersifat ATAU,
     * tetapi antar kelompok dan pencarian bersifat DAN — sama seperti halaman
     * Pedoman. Dulu filter memakai orWhereHas sehingga menambah filter justru
     * MEMPERLUAS hasil (dan mengabaikan kata kunci pencarian).
     *
     * @param  array<int, string>  $kategori  nama kategori terpilih
     * @param  array<int, string>  $jenis  nama jenis peraturan terpilih
     */
    public function scopeFilterPublik(Builder $query, ?string $cari, array $kategori = [], array $jenis = []): Builder
    {
        if ($cari) {
            $query->where(function ($q) use ($cari) {
                $q->where('nomor_peraturan', 'like', '%'.$cari.'%')
                    ->orWhere('judul_peraturan', 'like', '%'.$cari.'%')
                    ->orWhereHas('kategori', fn ($k) => $k->where('nama_kategori', 'like', '%'.$cari.'%'))
                    ->orWhereHas('data_jenis_peraturan', fn ($j) => $j->where('nama_jenis_peraturan', 'like', '%'.$cari.'%'));
            });
        }

        if ($kategori) {
            $query->whereHas('kategori', fn ($k) => $k->whereIn('nama_kategori', $kategori));
        }

        if ($jenis) {
            $query->whereHas('data_jenis_peraturan', fn ($j) => $j->whereIn('nama_jenis_peraturan', $jenis));
        }

        return $query;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('peraturan');
    }
}
