<?php

namespace App\Models\backend\MenuReferensi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class RefJenisPeraturan extends Model
{
    use HasFactory, LogsActivity;

    protected $primaryKey = 'id_jenis_peraturan';

    protected $table = 'ref_jenis_peraturan';

    protected $guarded = [];

    protected $fillable = ['nama_jenis_peraturan'];

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
            ->useLogName('jenis_peraturan');
    }
}
