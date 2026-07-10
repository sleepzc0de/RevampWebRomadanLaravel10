<?php

namespace App\Models\medsos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Medsos extends Model
{
    use HasFactory;

    protected $table = 'medsos';

    protected $guarded = [];

    protected $fillable = ['nama_medsos', 'link_medsos', 'logo_medsos'];

    protected $hidden = [
        'created_at',
        'updated_at',
        'id',
    ];

    protected static function booted(): void
    {
        // Segarkan cache footer setiap kali data medsos berubah
        $flush = fn () => Cache::forget('footer_medsos');
        static::saved($flush);
        static::deleted($flush);
    }
}
