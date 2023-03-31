<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ref_kategori extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_kategori';
    protected $table = 'ref_kategori';
    protected $guarded = [];
    protected $fillable = ['nama_kategori'];

    protected $hidden = [
        'created_at',
        'updated_at',
        'id',
    ];
}
