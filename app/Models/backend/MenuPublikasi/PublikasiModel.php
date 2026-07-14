<?php

namespace App\Models\backend\MenuPublikasi;

use App\Models\backend\RefKategori;
use App\Models\backend\RefStatus;
use App\Models\backend\RefTipe;
use Cohensive\OEmbed\Facades\OEmbed;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class PublikasiModel extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $table = 'publikasi';

    // NB: created_at sengaja fillable karena fitur backdate menulisnya
    // secara eksplisit. Tidak memakai $guarded agar hanya kolom di
    // $fillable yang bisa di-mass-assign.
    protected $fillable = [
        'judul', 'sub_judul', 'image', 'tipe', 'kategori', 'slug', 'isi',
        'penulis', 'pengedit', 'status', 'static_random_string', 'backdate',
        'created_at', 'updated_at', 'file', 'views', 'embedded_media', 'published_at',
    ];

    protected $casts = [
        'deleted_at' => 'datetime',
        'backdate' => 'datetime',
        'published_at' => 'datetime',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
        'id',
    ];

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(RefKategori::class, 'kategori', 'id_kategori');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(RefStatus::class, 'status', 'nama_status');
    }

    public function tipe(): BelongsTo
    {
        return $this->belongsTo(RefTipe::class, 'tipe', 'id_tipe');
    }

    /**
     * Get embedded media content as HTML
     *
     * @param  string  $url
     * @return string|null
     */
    public function getEmbeddedMediaHtml($url = null)
    {
        $mediaUrl = $url ?: $this->embedded_media;

        if (empty($mediaUrl)) {
            return null;
        }

        // Cache hasil embed agar tidak melakukan HTTP request ke provider
        // (YouTube/Vimeo/dll.) pada setiap kali halaman dirender.
        return Cache::remember(
            'oembed_'.md5($mediaUrl),
            now()->addDay(),
            function () use ($mediaUrl) {
                try {
                    $embed = OEmbed::get($mediaUrl);
                    if ($embed) {
                        return $embed->html(['width' => 560, 'height' => 315]);
                    }
                } catch (\Exception $e) {
                    \Log::error('Error embedding media: '.$e->getMessage());
                }

                // Jika OEmbed gagal / URL tidak didukung, cek apakah ini gambar
                if (preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $mediaUrl)) {
                    return '<img src="'.e($mediaUrl).'" alt="Embedded image" class="img-fluid">';
                }

                return null;
            }
        );
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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['judul', 'sub_judul', 'tipe', 'kategori', 'status', 'penulis', 'pengedit', 'backdate', 'published_at'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('publikasi');
    }
}
