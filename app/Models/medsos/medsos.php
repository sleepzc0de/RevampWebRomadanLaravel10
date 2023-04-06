<?php

namespace App\Models\medsos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class medsos extends Model
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
}
