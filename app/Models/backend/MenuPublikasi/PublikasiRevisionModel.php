<?php

namespace App\Models\backend\MenuPublikasi;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PublikasiRevisionModel extends Model
{
    public $timestamps = false;

    protected $table = 'publikasi_revisions';

    protected $guarded = [];

    protected $fillable = [
        'publikasi_id', 'judul', 'sub_judul', 'isi', 'kategori', 'tipe',
        'status', 'image', 'embedded_media', 'revised_by', 'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function publikasi(): BelongsTo
    {
        return $this->belongsTo(PublikasiModel::class, 'publikasi_id');
    }
}
