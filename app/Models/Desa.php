<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Desa extends Model
{
    protected $table = 'wilayah';          // mapping ke tabel wilayah
    protected $primaryKey = 'kode';        // karena kita pakai kode sebagai ID
    public $incrementing = false;          // karena kode bukan integer auto
    protected $keyType = 'string';         // kode berbentuk string
    public $timestamps = false;            // karena tabel wilayah tidak punya created_at dsb
}
