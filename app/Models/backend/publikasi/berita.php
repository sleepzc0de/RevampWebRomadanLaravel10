<?php

namespace App\Models\backend\publikasi;

use App\Models\backend\ref_kategori;
use App\Models\backend\ref_status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class berita extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'berita';
    protected $guarded = [];
    protected $fillable = ['judul', 'sub_judul', 'image', 'kategori', 'slug', 'isi', 'penulis', 'pengedit', 'status'];
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
        return $this->belongsTo(ref_status::class, 'status', 'id_status');
    }
}
