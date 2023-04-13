<?php

namespace App\Models\Login;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginModel extends Model
{
    use HasFactory;
    protected $table = 'login_gambar';
    protected $guarded = [];
    protected $fillable = ['nama_gambar', 'image'];

    protected $hidden = [
        'created_at',
        'updated_at',
        'id',
    ];
}
