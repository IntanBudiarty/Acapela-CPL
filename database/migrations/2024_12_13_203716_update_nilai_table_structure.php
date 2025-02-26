<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateNilaiTableStructure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nilai', function (Blueprint $table) {
            // Cek apakah foreign key sudah ada
            if (!Schema::hasColumn('nilai', 'mata_kuliah_id')) {
                $table->foreign('mata_kuliah_id')->references('id')->on('mata_kuliahs')->onDelete('cascade');
            }
        });
    }
    

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('nilai', function (Blueprint $table) {
            // Hapus kolom yang telah ditambahkan
            $table->dropForeign(['mata_kuliah_id']);
            $table->dropForeign(['mahasiswa_id']);
            $table->dropForeign(['rumusan_akhir_mk_id']);

            $table->dropColumn(['mata_kuliah_id', 'mahasiswa_id', 'rumusan_akhir_mk_id', 'nilai_cpmk', 'nilai_mk']);
        });
    }
}