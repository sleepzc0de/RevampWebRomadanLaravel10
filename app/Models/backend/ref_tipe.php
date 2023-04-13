<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ref_tipe extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_tipe';
    protected $table = 'ref_tipe';
    protected $guarded = [];
    protected $fillable = ['nama_tipe'];

    protected $hidden = [
        'created_at',
        'updated_at',
        'id_tipe',
    ];
}
