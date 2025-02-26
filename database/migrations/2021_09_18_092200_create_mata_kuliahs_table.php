<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMataKuliahsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mata_kuliahs', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique();
            $table->string('nama');
            $table->string('kelas')->nullable();
            $table->integer('sks');
            $table->integer('semester');
            $table->string('dosen_pengampu_1');
            $table->string('dosen_pengampu_2');
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
        Schema::table('mata_kuliahs', function (Blueprint $table) {
            $table->dropForeign(['dosen_pengampu_1']);  // Sesuaikan dengan nama kolom foreign key Anda
            $table->dropColumn('dosen_pengampu_1');
        });
    }
}
