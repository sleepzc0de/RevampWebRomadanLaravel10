<?php

namespace App\Models\backend\MenuProfile;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StrukturOrganisasiImageModel extends Model
{
    use HasFactory;

    protected $table = 'struktur_organisasi_images';

    protected $guarded = [];

    protected $fillable = ['struktur_organisasi_id', 'image_path', 'sort_order'];

    /**
     * Get the struktur organisasi that owns the image
     * No ordering to avoid SQL Server issues
     */
    public function strukturOrganisasi()
    {
        return $this->belongsTo(StrukturOrganisasiModel::class, 'struktur_organisasi_id');
    }
}
