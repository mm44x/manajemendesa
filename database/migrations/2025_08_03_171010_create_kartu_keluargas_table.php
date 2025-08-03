<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kartu_keluargas', function (Blueprint $table) {
            $table->id();
            $table->string('no_kk')->unique();
            $table->string('kepala_keluarga');
            $table->text('alamat');
            $table->string('rt', 3);
            $table->string('rw', 3);
            $table->string('desa');
            $table->string('kecamatan');
            $table->string('kabupaten');
            $table->string('kode_pos', 6);
            $table->date('tanggal_terbit');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kartu_keluargas');
    }
};
