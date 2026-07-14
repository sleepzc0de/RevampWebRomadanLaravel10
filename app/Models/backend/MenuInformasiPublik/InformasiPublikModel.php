<?php

namespace App\Models\backend\MenuInformasiPublik;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class InformasiPublikModel extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'informasi_publik';

    protected $guarded = [];

    protected $fillable = [
        'judul_list_informasi',
        'isi_list_informasi',
        'link_list_informasi',
    ];

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
            ->useLogName('informasi_publik');
    }
}
