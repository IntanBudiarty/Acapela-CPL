<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKodeMkToCpmksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::table('cpmks', function (Blueprint $table) {
        $table->string('kode_mk')->nullable(); // Menambahkan kolom kode_mk
    });
}

public function down()
{
    Schema::table('cpmks', function (Blueprint $table) {
        $table->dropColumn('kode_mk'); // Menghapus kolom kode_mk jika migrasi dibatalkan
    });
}
}
