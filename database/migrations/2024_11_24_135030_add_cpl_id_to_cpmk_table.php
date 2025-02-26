<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCplIdToCpmkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cpmks', function (Blueprint $table) {
            // Menambahkan kolom kode_cpl yang merujuk ke kode di tabel cpls
            $table->string('cpl_id')->nullable();  // Menambahkan kolom kode_cpl
            $table->foreign('cpl_id')->references('id')->on('cpls')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cpmks', function (Blueprint $table) {
            // Menghapus foreign key dan kolom
            $table->dropForeign(['cpl_id']);
            $table->dropColumn('cpl_id');
        });
    }
}
