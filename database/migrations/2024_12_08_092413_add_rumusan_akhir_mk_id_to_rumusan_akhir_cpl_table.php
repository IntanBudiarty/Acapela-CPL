<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRumusanAkhirMkIdToRumusanAkhirCplTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rumusan_akhir_cpl', function (Blueprint $table) {
            $table->unsignedBigInteger('rumusan_akhir_mk_id')->nullable(); // Menambahkan kolom rumusan_akhir_mk_id
            $table->foreign('rumusan_akhir_mk_id')->references('id')->on('rumusan_akhir_mk')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('rumusan_akhir_cpl', function (Blueprint $table) {
            $table->dropForeign(['rumusan_akhir_mk_id']);
            $table->dropColumn('rumusan_akhir_mk_id');
        });
    }
}