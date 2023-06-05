<?php

namespace App\Models\backend\MenuFAQ;

use App\Models\backend\ref_kategori;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FAQModel extends Model
{
    use HasFactory;
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
        return $this->belongsTo(ref_kategori::class, 'kategori', 'id_kategori');
    }
}
