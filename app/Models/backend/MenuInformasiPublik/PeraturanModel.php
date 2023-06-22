<?php

namespace App\Models\backend\MenuInformasiPublik;

use App\Models\backend\MenuReferensi\ref_jenis_peraturan;
use App\Models\backend\MenuReferensi\ref_peraturan_status;
use App\Models\backend\ref_kategori;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeraturanModel extends Model
{
    use HasFactory;

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
        return $this->belongsTo(ref_kategori::class, 'kategori', 'id_kategori')->withDefault(['nama_jenis_peraturan' => 'KATEGORI BELUM DIPILIH']);
    }

    public function jenis_peraturan()
    {
        return $this->belongsTo(ref_jenis_peraturan::class, 'jenis_peraturan', 'id_jenis_peraturan')->withDefault(['nama_jenis_peraturan' => 'JENIS PERATURAN BELUM DIPILIH']);
    }

    public function status_peraturan()
    {
        return $this->belongsTo(ref_peraturan_status::class, 'status_peraturan', 'id_ref_peraturan_status')->withDefault(['nama_peraturan_status' => 'STATUS BELUM DIISI']);
    }
}
