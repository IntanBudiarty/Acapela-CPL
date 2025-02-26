<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBobotToMataKuliahsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::table('mata_kuliahs', function (Blueprint $table) {
        $table->integer('bobot')->nullable(); // Menambahkan kolom bobot
    });
}

public function down()
{
    Schema::table('mata_kuliahs', function (Blueprint $table) {
        $table->dropColumn('bobot'); // Menghapus kolom bobot jika rollback
    });
}

}
