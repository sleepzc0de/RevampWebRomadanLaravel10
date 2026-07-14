<?php

namespace App\Models\backend\MenuInformasiPublik;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class AplikasiModel extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'aplikasi';

    protected $guarded = [];

    protected $fillable = ['judul_aplikasi', 'sub_judul_aplikasi', 'link_aplikasi', 'image'];

    protected $hidden = [
        'created_at',
        'updated_at',
        'id',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('aplikasi');
    }
}
