<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class RefStatus extends Model
{
    use HasFactory, LogsActivity;

    protected $primaryKey = 'id_status';

    protected $table = 'ref_status';

    protected $guarded = [];

    protected $fillable = ['nama_status'];

    protected $hidden = [
        'created_at',
        'updated_at',
        'id_status',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('status');
    }
}
