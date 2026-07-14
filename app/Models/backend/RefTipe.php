<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class RefTipe extends Model
{
    use HasFactory, LogsActivity;

    protected $primaryKey = 'id_tipe';

    protected $table = 'ref_tipe';

    protected $guarded = [];

    protected $fillable = ['nama_tipe'];

    protected $hidden = [
        'created_at',
        'updated_at',
        'id_tipe',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('tipe');
    }
}
