<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKodeCpmkToMataKuliahCpmk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mata_kuliah_cpmk', function (Blueprint $table) {
            $table->string('kode_cpmk')->nullable();  // Menambahkan kolom kode_cpmk
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mata_kuliah_cpmk', function (Blueprint $table) {
            $table->dropColumn('kode_cpmk');
        });
    }
}
