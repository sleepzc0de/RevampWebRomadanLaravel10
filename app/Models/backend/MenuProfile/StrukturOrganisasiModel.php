<?php

namespace App\Models\backend\MenuProfile;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StrukturOrganisasiModel extends Model
{
    use HasFactory;
    protected $table = 'struktur_organisasi';
    protected $guarded = [];
    protected $fillable = ['struktur', 'image', 'judul'];

    protected $hidden = [
        'created_at',
        'updated_at',
        'id',
    ];
}
