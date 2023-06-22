<?php

namespace App\Models\backend\MenuReferensi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ref_jenis_peraturan extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_jenis_peraturan';
    protected $table = 'ref_jenis_peraturan';
    protected $guarded = [];
    protected $fillable = ['nama_jenis_peraturan'];

    protected $hidden = [
        'created_at',
        'updated_at',
        'id',
    ];
}
