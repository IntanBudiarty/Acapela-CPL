<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveRumusanIdFromNilaisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nilais', function (Blueprint $table) {
            // Hapus foreign key constraint
            $table->dropForeign(['rumusan_id']); // Menggunakan nama kolom
            
            // Hapus kolom rumusan_id
            $table->dropColumn('rumusan_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('nilais', function (Blueprint $table) {
            // Tambahkan kembali kolom rumusan_id
            $table->unsignedBigInteger('rumusan_id')->nullable();

            // Tambahkan kembali foreign key constraint
            $table->foreign('rumusan_id')->references('id')->on('tabel_tujuan_foreign_key')->onDelete('cascade');
        });
    }
}
