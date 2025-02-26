<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKetercapaianTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ketercapaian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswas');
            $table->foreignId('mata_kuliah_id')->constrained()->onDelete('cascade');
            $table->string('nama_mk');
            $table->string('kd_cpl');
            $table->string('kd_cpmk');
            $table->integer('skor_maksimal');
            $table->integer('total_skor');
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
        Schema::dropIfExists('ketercapaian');
    }
}
