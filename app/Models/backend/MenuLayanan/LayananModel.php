<?php

namespace App\Models\backend\MenuLayanan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class LayananModel extends Model
{
    use HasFactory, LogsActivity;

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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontLogEmptyChanges()
            ->useLogName('layanan');
    }
}
