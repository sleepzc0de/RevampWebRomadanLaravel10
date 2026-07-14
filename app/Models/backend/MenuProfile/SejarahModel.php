<?php

namespace App\Models\backend\MenuProfile;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class SejarahModel extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'sejarah';

    protected $guarded = [];

    protected $fillable = ['sejarah', 'image', 'judul', 'media'];

    protected $hidden = [
        'created_at',
        'updated_at',
        'id',
    ];

    protected $casts = [
        'media' => 'json',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('sejarah');
    }
}
