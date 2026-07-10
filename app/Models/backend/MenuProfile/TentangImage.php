<?php

namespace App\Models\backend\MenuProfile;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TentangImage extends Model
{
    use HasFactory;

    protected $table = 'tentang_images';

    protected $fillable = ['tentang_id', 'image_path'];

    /**
     * Get the tentang that owns this image
     */
    public function tentang()
    {
        return $this->belongsTo(TentangModel::class, 'tentang_id');
    }
}
