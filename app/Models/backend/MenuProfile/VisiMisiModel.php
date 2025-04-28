<?php

namespace App\Models\backend\MenuProfile;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisiMisiModel extends Model
{
    use HasFactory;
    protected $table = 'visimisi';
    protected $guarded = [];
    protected $fillable = ['visi', 'misi', 'image', 'judul', 'video_url'];

    protected $hidden = [
        'created_at',
        'updated_at',
        'id',
    ];

    // Relationship with images
    public function images()
    {
        return $this->hasMany(VisiMisiImageModel::class, 'visimisi_id', 'id')->orderBy('sort_order');
    }
}
