<?php

namespace App\Models\backend\Tim;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeveloperModel extends Model
{
    protected $table = 'developers';

    protected $fillable = ['name', 'skill', 'photo'];

    use HasFactory;
}
