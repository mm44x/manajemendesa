<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wilayah extends Model
{
    protected $table = 'wilayah'; // Pastikan ini nama tabel kamu
    protected $primaryKey = 'kode'; // Kalau kode adalah primary key
    public $incrementing = false; // Kalau 'kode' bukan integer autoincrement
    protected $keyType = 'string'; // Kalau kodenya seperti '33.01' atau string

    protected $fillable = ['kode', 'nama'];
}
