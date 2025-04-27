<?php

namespace App\Models\backend\MenuPublikasi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PublikasiImage extends Model
{
    use HasFactory;

    protected $table = 'publikasi_images';

    protected $fillable = [
        'publikasi_id',
        'image_path',
        'is_primary',
        'sort_order'
    ];

    public function publikasi()
    {
        return $this->belongsTo(PublikasiModel::class, 'publikasi_id');
    }
}
