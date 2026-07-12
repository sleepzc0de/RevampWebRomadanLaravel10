<?php

namespace App\Models\backend\MenuProfile;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class StrukturOrganisasiModel extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'struktur_organisasi';

    protected $guarded = [];

    protected $fillable = ['struktur', 'image', 'judul', 'video_url', 'layout_type'];

    protected $hidden = [
        'created_at',
        'updated_at',
        'id',
    ];

    /**
     * Get all additional images related to this struktur organisasi
     * No SQL ordering to avoid SQL Server issues
     */
    public function additionalImages()
    {
        // Just define the relationship without ordering
        return $this->hasMany(StrukturOrganisasiImageModel::class, 'struktur_organisasi_id');
    }

    /**
     * Get the YouTube video ID from the full URL
     *
     * @return string|null
     */
    public function getYoutubeIdAttribute()
    {
        if (empty($this->video_url)) {
            return null;
        }

        $pattern =
            '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i';

        preg_match($pattern, $this->video_url, $matches);

        Log::debug('YouTube URL parsing', [
            'url' => $this->video_url,
            'matches' => $matches,
            'id' => isset($matches[1]) ? $matches[1] : null,
        ]);

        return isset($matches[1]) ? $matches[1] : null;
    }

    /**
     * Convert layout_type to bootstrap classes for responsive design
     *
     * @return array
     */
    public function getResponsiveClassesAttribute()
    {
        switch ($this->layout_type) {
            case 'wide':
                return [
                    'content' => 'col-md-4',
                    'media' => 'col-md-8',
                ];
            case 'compact':
                return [
                    'content' => 'col-md-8',
                    'media' => 'col-md-4',
                ];
            default: // standard
                return [
                    'content' => 'col-md-6',
                    'media' => 'col-md-6',
                ];
        }
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontLogEmptyChanges()
            ->useLogName('struktur_organisasi');
    }
}
