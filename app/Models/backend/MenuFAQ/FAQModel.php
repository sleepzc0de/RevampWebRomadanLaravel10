<?php

namespace App\Models\backend\MenuFAQ;

use App\Models\backend\RefKategori;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class FAQModel extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'faq';

    protected $guarded = [];

    protected $fillable = ['judul', 'faq_judul', 'faq_isi', 'kategori', 'penulis'];

    protected $hidden = [
        'created_at',
        'updated_at',
        'id',
    ];

    public function kategori()
    {
        return $this->belongsTo(RefKategori::class, 'kategori', 'id_kategori');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('faq');
    }
}
