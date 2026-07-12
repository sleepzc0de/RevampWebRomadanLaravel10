<?php

namespace App\Models\backend\MenuKegiatan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class KegiatanModel extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $table = 'kegiatan';

    protected $guarded = [];

    protected $fillable = ['judul', 'tempat', 'image', 'file', 'slug', 'isi', 'tanggal_mulai', 'tanggal_selesai', 'link', 'static_random_string'];

    protected $dates = ['deleted_at'];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
        'id',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontLogEmptyChanges()
            ->useLogName('kegiatan');
    }
}
