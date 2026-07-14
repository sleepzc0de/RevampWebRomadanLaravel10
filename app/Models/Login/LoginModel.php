<?php

namespace App\Models\Login;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class LoginModel extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'login_gambar';

    protected $guarded = [];

    protected $fillable = ['nama_gambar', 'image'];

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
            ->useLogName('login_gambar');
    }
}
