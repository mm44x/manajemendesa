<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnggotaKeluarga extends Model
{
    public function kartuKeluarga()
    {
        return $this->belongsTo(KartuKeluarga::class);
    }

    protected $fillable = [
        'kartu_keluarga_id',
        'nik',
        'nama',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'hubungan',
        'agama',
        'pendidikan',
        'pekerjaan',
    ];
    use HasFactory;
}
