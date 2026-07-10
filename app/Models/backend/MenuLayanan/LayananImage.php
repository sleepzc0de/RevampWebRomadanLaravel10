<?php

namespace App\Models\backend\MenuLayanan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LayananImage extends Model
{
    use HasFactory;

    protected $table = 'layanan_images';

    protected $fillable = ['layanan_id', 'image_path'];

    /**
     * Get the layanan that owns the image
     */
    public function layanan()
    {
        return $this->belongsTo(LayananModel::class, 'layanan_id');
    }
}
