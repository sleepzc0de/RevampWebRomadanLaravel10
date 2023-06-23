<?php

namespace App\Models\backend\MenuInformasiPublik;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AplikasiModel extends Model
{
    use HasFactory;
    protected $table = 'aplikasi';
    protected $guarded = [];
    protected $fillable = ['judul_aplikasi', 'sub_judul_aplikasi', 'link_aplikasi', 'image'];

    protected $hidden = [
        'created_at',
        'updated_at',
        'id',
    ];
}
