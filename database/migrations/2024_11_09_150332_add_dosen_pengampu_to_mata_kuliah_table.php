<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDosenPengampuToMataKuliahTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mata_kuliahs', function (Blueprint $table) {
            if (!Schema::hasColumn('mata_kuliahs', 'dosen_pengampu_1')) {
                $table->bigInteger('dosen_pengampu_1')->unsigned()->nullable();
            }
            if (!Schema::hasColumn('mata_kuliahs', 'dosen_pengampu_2')) {
                $table->bigInteger('dosen_pengampu_2')->unsigned()->nullable();
            }
        });
    }

public function down()
{
    Schema::table('mata_kuliah', function (Blueprint $table) {
        $table->dropForeign(['dosen_pengampu_1']);
        $table->dropForeign(['dosen_pengampu_2']);
        $table->dropColumn(['dosen_pengampu_1', 'dosen_pengampu_2']);
    });
}
}