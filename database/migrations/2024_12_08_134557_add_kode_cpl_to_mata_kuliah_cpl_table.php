<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKodeCplToMataKuliahCplTable extends Migration
{
    public function up()
    {
        Schema::table('mata_kuliah_cpl', function (Blueprint $table) {
            $table->string('kode_cpl')->nullable(); // Menambahkan kolom kode_cpl
        });
    }

    public function down()
    {
        Schema::table('mata_kuliah_cpl', function (Blueprint $table) {
            $table->dropColumn('kode_cpl'); // Hapus kolom jika rollback
        });
    }
}
