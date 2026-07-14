<?php

namespace App\Models\backend\MenuInformasiPublik;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class InfopublikHomeModel extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'infopublik_home';

    protected $guarded = [];

    protected $fillable = ['judul', 'isi'];

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
            ->useLogName('infopublik_home');
    }
}
