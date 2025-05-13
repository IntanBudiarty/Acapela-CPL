<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSemesterToMahasiswaMataKuliahTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mahasiswa_mata_kuliah', function (Blueprint $table) {
            $table->unsignedTinyInteger('semester')->default(1)->after('mata_kuliah_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mahasiswa_mata_kuliah', function (Blueprint $table) {
            $table->dropColumn('semester');
        });
    }
}
