<?php

namespace App\Models\backend\MenuProfile;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TentangModel extends Model
{
    use HasFactory;
    protected $table = 'tentang';
    protected $guarded = [];
    protected $fillable = ['tentang', 'image', 'judul'];

    protected $hidden = [
        'created_at',
        'updated_at',
        'id',
    ];
}
