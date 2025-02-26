<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNilaisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nilais', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mata_kuliah_id')->constrained('mata_kuliahs');
            $table->foreignId('mahasiswa_id')->constrained('mahasiswas');
            $table->unsignedBigInteger('rumusan_id')->nullable();
            $table->foreignId('rumusan_akhir_mk_id')->constrained('rumusan_akhir_mk');
            $table->integer('nilai')->nullable();
            $table->integer('total')->nullable();
            $table->integer('skor_maks')->default(100);
            $table->timestamps();
            $table->foreign('mata_kuliah_id')->references('id')->on('mata_kuliahs')->onDelete('cascade');
            $table->foreign('mahasiswa_id')->references('id')->on('mahasiswas')->onDelete('cascade');
            $table->foreign('rumusan_id')->references('id')->on('rumusan_akhir_mk')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nilais');
    }
}
