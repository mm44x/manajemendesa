<?php
// app/Models/SetoranIuran.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SetoranIuran extends Model
{
    use HasFactory;

    protected $fillable = [
        'iuran_id',
        'kartu_keluarga_id',
        'tanggal_setor',
        'periode_label',
        'nominal_dibayar',
        'dibayarkan_oleh',
        'created_by',
    ];

    public function iuran()
    {
        return $this->belongsTo(Iuran::class, 'iuran_id');
    }

    public function kartuKeluarga()
    {
        return $this->belongsTo(KartuKeluarga::class, 'kartu_keluarga_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
