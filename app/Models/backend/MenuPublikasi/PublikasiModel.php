<?php

namespace App\Models\backend\MenuPublikasi;

use App\Models\backend\ref_kategori;
use App\Models\backend\ref_status;
use App\Models\backend\ref_tipe;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cohensive\OEmbed\Facades\OEmbed;

class PublikasiModel extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'publikasi';
    protected $guarded = [];
    protected $fillable = ['judul', 'sub_judul', 'image', 'tipe', 'kategori', 'slug', 'isi', 'penulis', 'pengedit', 'status','static_random_string','backdate','created_at',
    'updated_at','file'];
    protected $dates = ['deleted_at'];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
        'id',
    ];

    public function kategori()
    {
        return $this->belongsTo(ref_kategori::class, 'kategori', 'id_kategori');
    }

    public function status()
    {
        return $this->belongsTo(ref_status::class, 'status', 'nama_status');
    }

    public function tipe()
    {
        return $this->belongsTo(ref_tipe::class, 'tipe', 'id_tipe');
    }

    public function getVideoAtrribute($value){
        $embed = OEmbed::get($value);
        if ($embed) {
            return $embed->html(['width'=>200]);
        }
    }
}
