<?php

namespace App\Models\backend\MenuKegiatan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KegiatanModel extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'kegiatan';
    protected $guarded = [];
    protected $fillable = ['judul', 'tempat', 'image', 'file', 'slug', 'isi', 'tanggal_mulai', 'tanggal_selesai', 'link'];
    protected $dates = ['deleted_at'];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
        'id',
    ];
}
