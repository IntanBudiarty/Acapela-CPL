<?php

use App\Models\MataKuliah;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCpmksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cpmks', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(MataKuliah::class);
            $table->string('kode_cpmk')->unique();
            $table->string('nama_cpmk');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cpmks');
    }
}
