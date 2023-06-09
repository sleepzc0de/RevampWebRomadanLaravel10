<?php

namespace App\Models\backend\MenuInformasiPublik;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InfopublikHomeModel extends Model
{
    use HasFactory;
    protected $table = 'infopublik_home';
    protected $guarded = [];
    protected $fillable = ['judul', 'isi'];

    protected $hidden = [
        'created_at',
        'updated_at',
        'id',
    ];
}
