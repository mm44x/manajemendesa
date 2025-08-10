<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KartuKeluarga extends Model
{
    use HasFactory;
    public function desa()
    {
        return $this->belongsTo(\App\Models\Desa::class, 'desa_id', 'kode');
    }
    protected $fillable = [
        'no_kk',
        'kepala_keluarga',
        'alamat',
        'rt',
        'rw',
        'desa_id',
        'kode_pos',
        'tanggal_terbit',
    ];

    public function anggota()
    {
        return $this->hasMany(AnggotaKeluarga::class);
    }

    public function setoranIurans()
    {
        return $this->hasMany(\App\Models\SetoranIuran::class, 'kartu_keluarga_id');
    }

    public function anggotaKeluargas()
    {
        return $this->hasMany(\App\Models\AnggotaKeluarga::class);
    }
}
