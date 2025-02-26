<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCplIdToDetailCplsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('detail_cpls', function (Blueprint $table) {
            // Menambahkan foreign key pada kolom cpl_id yang sudah ada
            $table->foreign('cpl_id')
                  ->references('id')
                  ->on('cpls')
                  ->onDelete('cascade');
        });
    }
    public function down()
    {
        Schema::table('detail_cpls', function (Blueprint $table) {
            // Menghapus foreign key dan kolom jika migrasi dibatalkan
            $table->dropForeign(['cpl_id']);
        });
    }
}