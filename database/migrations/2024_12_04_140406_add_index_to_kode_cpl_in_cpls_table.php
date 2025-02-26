<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexToKodeCplInCplsTable extends Migration
{
    public function up()
    {
        Schema::table('cpls', function (Blueprint $table) {
            // Menambahkan index pada kolom 'kode_cpl'
            $table->index('kode_cpl');
        });
    }

    public function down()
    {
        Schema::table('cpls', function (Blueprint $table) {
            // Menghapus index jika migrasi di-rollback
            $table->dropIndex(['kode_cpl']);
        });
    }
}
