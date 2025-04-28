<?php

namespace App\Models\backend\MenuProfile;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisiMisiImageModel extends Model
{
    use HasFactory;

    protected $table = 'visimisi_images';
    protected $fillable = ['visimisi_id', 'image', 'sort_order'];

    /**
     * Get the visimisi that owns the image.
     */
    public function visimisi()
    {
        return $this->belongsTo(VisiMisiModel::class, 'visimisi_id');
    }
}
