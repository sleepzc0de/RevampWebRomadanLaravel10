<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class file extends Model
{
    use HasFactory, SoftDeletes;
    protected $primaryKey = 'id';
    protected $table = 'file';
    protected $guarded = [];
    protected $fillable = ['nama_file', 'image_file', 'kategori_file', 'status_file', 'isi_file', 'pembuat_file'];
    protected $dates = ['deleted_at'];


    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
        'id',
    ];
}
