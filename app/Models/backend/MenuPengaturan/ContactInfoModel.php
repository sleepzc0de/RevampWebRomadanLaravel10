<?php

namespace App\Models\backend\MenuPengaturan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class ContactInfoModel extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'contact_infos';

    protected $guarded = [];

    protected $fillable = ['email', 'whatsapp', 'address'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontLogEmptyChanges()
            ->useLogName('contact_info');
    }
}
