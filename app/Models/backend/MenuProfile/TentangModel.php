<?php

namespace App\Models\backend\MenuProfile;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TentangModel extends Model
{
    use HasFactory;

    protected $table = 'tentang';

    protected $guarded = [];

    protected $fillable = ['tentang', 'image', 'judul', 'excerpt', 'video_url'];

    protected $hidden = [
        'created_at',
        'updated_at',
        'id',
    ];

    /**
     * Get all additional images for this tentang
     */
    public function additionalImages()
    {
        return $this->hasMany(TentangImage::class, 'tentang_id');
    }
}
