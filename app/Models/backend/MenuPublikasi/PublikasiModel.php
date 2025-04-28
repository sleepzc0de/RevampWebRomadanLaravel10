<?php

namespace App\Models\backend\MenuPublikasi;

use App\Models\backend\ref_kategori;
use App\Models\backend\ref_status;
use App\Models\backend\ref_tipe;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cohensive\OEmbed\Facades\OEmbed;

class PublikasiModel extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'publikasi';
    protected $guarded = [];
    protected $fillable = [
        'judul', 'sub_judul', 'image', 'tipe', 'kategori', 'slug', 'isi',
        'penulis', 'pengedit', 'status', 'static_random_string', 'backdate',
        'created_at', 'updated_at', 'file', 'views', 'embedded_media'
    ];
    protected $dates = ['deleted_at'];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
        'id',
    ];

    public function kategori()
    {
        return $this->belongsTo(ref_kategori::class, 'kategori', 'id_kategori');
    }

    public function status()
    {
        return $this->belongsTo(ref_status::class, 'status', 'nama_status');
    }

    public function tipe()
    {
        return $this->belongsTo(ref_tipe::class, 'tipe', 'id_tipe');
    }

    /**
     * Get embedded media content as HTML
     *
     * @param string $url
     * @return string|null
     */
    public function getEmbeddedMediaHtml($url = null)
    {
        $mediaUrl = $url ?: $this->embedded_media;

        if (empty($mediaUrl)) {
            return null;
        }

        try {
            $embed = OEmbed::get($mediaUrl);
            if ($embed) {
                return $embed->html(['width' => 560, 'height' => 315]);
            }
        } catch (\Exception $e) {
            \Log::error('Error embedding media: ' . $e->getMessage());
        }

        // If OEmbed fails or URL is not supported, try to determine if it's an image
        if (preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $mediaUrl)) {
            return '<img src="' . e($mediaUrl) . '" alt="Embedded image" class="img-fluid">';
        }

        return null;
    }

    public function getVideoAttribute()
    {
        return $this->getEmbeddedMediaHtml($this->embedded_media);
    }

    public function images()
    {
        return $this->hasMany(PublikasiImage::class, 'publikasi_id');
    }

    public function primaryImage()
    {
        return $this->hasOne(PublikasiImage::class, 'publikasi_id')->where('is_primary', true);
    }
}
