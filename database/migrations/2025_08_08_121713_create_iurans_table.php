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
        Schema::create('iurans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_iuran');
            $table->text('deskripsi')->nullable();
            $table->enum('tipe', ['sekali', 'mingguan', 'bulanan']);
            $table->enum('jenis_setoran', ['tetap', 'bebas']);
            $table->integer('nominal')->nullable(); // hanya untuk jenis_setoran tetap
            $table->foreignId('created_by')->constrained('users');
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
        Schema::dropIfExists('iurans');
    }
};
