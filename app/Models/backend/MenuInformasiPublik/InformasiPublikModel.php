<?php

namespace App\Models\backend\MenuInformasiPublik;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InformasiPublikModel extends Model
{
    use HasFactory;
    protected $table = 'informasi_publik';
    protected $guarded = [];
    protected $fillable = ['isi', 'judul'];

    protected $hidden = [
        'created_at',
        'updated_at',
        'id',
    ];
}
