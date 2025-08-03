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
        Schema::table('kartu_keluargas', function (Blueprint $table) {
            $table->string('desa_id')->after('rw'); // tipe string karena kode wilayah
        });
    }

    public function down()
    {
        Schema::table('kartu_keluargas', function (Blueprint $table) {
            $table->dropColumn('desa_id');
        });
    }
};
