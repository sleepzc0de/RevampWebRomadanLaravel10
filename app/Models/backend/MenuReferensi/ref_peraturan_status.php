<?php

namespace App\Models\backend\MenuReferensi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ref_peraturan_status extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_ref_peraturan_status';
    protected $table = 'ref_peraturan_status';
    protected $guarded = [];
    protected $fillable = ['nama_peraturan_status'];

    protected $hidden = [
        'created_at',
        'updated_at',
        'id',
    ];
}
