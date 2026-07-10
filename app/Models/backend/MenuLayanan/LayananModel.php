<?php

namespace App\Models\backend\MenuLayanan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LayananModel extends Model
{
    use HasFactory;

    protected $table = 'layanan';

    protected $guarded = [];

    protected $fillable = ['layanan', 'image', 'judul', 'video_url'];

    protected $hidden = [
        'created_at',
        'updated_at',
        'id',
    ];

    /**
     * Get the additional images for the layanan
     */
    public function additionalImages()
    {
        return $this->hasMany(LayananImage::class, 'layanan_id');
    }
}
