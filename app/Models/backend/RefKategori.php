<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class RefKategori extends Model
{
    use HasFactory, LogsActivity;

    protected $primaryKey = 'id_kategori';

    protected $table = 'ref_kategori';

    protected $guarded = [];

    protected $fillable = ['nama_kategori'];

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
            ->useLogName('kategori');
    }
}
