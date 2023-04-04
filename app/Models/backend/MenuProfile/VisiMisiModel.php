<?php

namespace App\Models\backend\MenuProfile;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisiMisiModel extends Model
{
    use HasFactory;
    protected $table = 'visimisi';
    protected $guarded = [];
    protected $fillable = ['visi', 'misi', 'image', 'judul'];

    protected $hidden = [
        'created_at',
        'updated_at',
        'id',
    ];
}
