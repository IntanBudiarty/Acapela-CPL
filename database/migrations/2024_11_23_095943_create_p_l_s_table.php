<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePLSTable extends Migration
{
    public function up()
    {
        Schema::create('p_l_s', function (Blueprint $table) {
            $table->id(); // ID untuk tabel ini
            $table->string('kode_pl'); // Kolom untuk kode PL
            $table->text('deskripsi'); // Kolom untuk deskripsi PL
            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('p_l_s'); // Menghapus tabel jika rollback migrasi dilakukan
    }
}
