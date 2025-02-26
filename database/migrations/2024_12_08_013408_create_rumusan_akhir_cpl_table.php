<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRumusanAkhirCplTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::create('rumusan_akhir_cpl', function (Blueprint $table) {
        $table->id(); // ID Auto Increment
        $table->string('kd_cpl'); // Kode CPL
        $table->foreignId('mata_kuliah_id')->constrained('mata_kuliahs'); // Mata Kuliah
        $table->string('nama_mk'); // Nama Mata Kuliah
        $table->string('cpmk'); // Kode CPMK
        $table->integer('skor_maksimal'); // Skor Maksimal
        $table->integer('total_skor'); // Total Skor
        $table->foreignId('rumusan_akhir_mk_id')->constrained('rumusan_akhir_mk'); // Relasi ke tabel rumusan_akhir_mk
        $table->timestamps(); // created_at dan updated_at
    });
}

    public function down()
    {
        Schema::dropIfExists('rumusan_akhir_cpl');
    }
}