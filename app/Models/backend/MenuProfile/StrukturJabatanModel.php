<?php

namespace App\Models\backend\MenuProfile;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class StrukturJabatanModel extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'struktur_jabatan';

    protected $guarded = [];

    protected $fillable = ['nama_jabatan', 'parent_id', 'urutan'];

    /**
     * No SQL ordering — dilakukan di PHP (konsisten dengan model
     * StrukturOrganisasi lain, menghindari isu ordering di SQL Server).
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function pejabat(): HasMany
    {
        return $this->hasMany(StrukturPejabatModel::class, 'jabatan_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontLogEmptyChanges()
            ->useLogName('struktur_jabatan');
    }
}
