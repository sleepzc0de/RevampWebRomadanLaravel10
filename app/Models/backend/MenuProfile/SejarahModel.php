<?php

namespace App\Models\backend\MenuProfile;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SejarahModel extends Model
{
    use HasFactory;
    protected $table = 'sejarah';
    protected $guarded = [];
    protected $fillable = ['sejarah', 'image', 'judul'];

    protected $hidden = [
        'created_at',
        'updated_at',
        'id',
    ];
}
