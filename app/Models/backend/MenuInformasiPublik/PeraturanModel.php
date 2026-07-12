<?php

namespace App\Models\backend\MenuInformasiPublik;

use App\Models\backend\MenuReferensi\RefJenisPeraturan;
use App\Models\backend\MenuReferensi\RefPeraturanStatus;
use App\Models\backend\RefKategori;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontLogEmptyChanges()
            ->useLogName('peraturan');
    }
}
