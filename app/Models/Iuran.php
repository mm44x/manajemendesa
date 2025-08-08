<?php
// app/Models/Iuran.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Iuran extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_iuran',
        'deskripsi',
        'tipe',
        'jenis_setoran',
        'nominal',
        'created_by',
    ];

    // Relasi ke user pembuat
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Relasi ke KK yang jadi peserta iuran
    public function kartuKeluargas()
    {
        return $this->belongsToMany(KartuKeluarga::class, 'iuran_kks', 'iuran_id', 'kartu_keluarga_id');
    }

    // Relasi ke semua setoran untuk iuran ini
    public function setoranIurans()
    {
        return $this->hasMany(SetoranIuran::class, 'iuran_id');
    }
}
