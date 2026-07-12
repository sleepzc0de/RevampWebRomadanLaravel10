<?php

namespace App\Models\backend\MenuProfile;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StrukturPejabatModel extends Model
{
    use HasFactory;

    protected $table = 'struktur_pejabat';

    protected $guarded = [];

    protected $fillable = ['jabatan_id', 'nama', 'foto', 'urutan'];

    public function jabatan()
    {
        return $this->belongsTo(StrukturJabatanModel::class, 'jabatan_id');
    }

    public function getFotoUrlAttribute(): ?string
    {
        return $this->foto ? asset("storage/romadan_gambar_web/{$this->foto}") : null;
    }
}
