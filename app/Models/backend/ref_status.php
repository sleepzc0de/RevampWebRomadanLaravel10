<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ref_status extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_status';
    protected $table = 'ref_status';
    protected $guarded = [];
    protected $fillable = ['nama_status'];

    protected $hidden = [
        'created_at',
        'updated_at',
        'id_status',
    ];
}
