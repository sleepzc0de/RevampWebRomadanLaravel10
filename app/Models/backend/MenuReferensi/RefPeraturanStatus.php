<?php

namespace App\Models\backend\MenuReferensi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class RefPeraturanStatus extends Model
{
    use HasFactory, LogsActivity;

    protected $primaryKey = 'id_ref_peraturan_status';

    protected $table = 'ref_peraturan_status';

    protected $guarded = [];

    protected $fillable = ['nama_peraturan_status'];

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
            ->dontLogEmptyChanges()
            ->useLogName('status_peraturan');
    }
}
