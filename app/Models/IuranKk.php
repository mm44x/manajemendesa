<?php
// app/Models/IuranKk.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IuranKk extends Model
{
    use HasFactory;

    protected $table = 'iuran_kks';

    protected $fillable = [
        'iuran_id',
        'kartu_keluarga_id',
    ];

    public function iuran()
    {
        return $this->belongsTo(Iuran::class, 'iuran_id');
    }

    public function kartuKeluarga()
    {
        return $this->belongsTo(KartuKeluarga::class, 'kartu_keluarga_id');
    }
}
