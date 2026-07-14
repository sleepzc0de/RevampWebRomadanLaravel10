<?php

namespace App\Models\backend\MenuInformasiPublik;

use App\Models\backend\RefKategori;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class PedomanModel extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'pedoman';

    protected $guarded = [];

    protected $fillable = ['judul_pedoman', 'deskripsi', 'file', 'kategori', 'tanggal_terbit', 'slug'];

    protected $hidden = [
        'created_at',
        'updated_at',
        'id',
    ];

    /**
     * NB: sengaja TIDAK dinamai kategori() — nama itu bentrok dengan kolom
     * FK `kategori` di tabel ini, sehingga $model->kategori akan
     * mengembalikan nilai kolom mentah (string), bukan relasinya.
     */
    public function dataKategori(): BelongsTo
    {
        return $this->belongsTo(RefKategori::class, 'kategori', 'id_kategori');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('pedoman');
    }
}
