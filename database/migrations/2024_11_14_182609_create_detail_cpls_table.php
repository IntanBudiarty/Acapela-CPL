<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailCplsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_cpls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cpmk_id')->constrained()->onDelete('cascade');
            $table->string('nama_cpmk');
            $table->foreignId('mata_kuliah_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('cpl_id');
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
        Schema::dropIfExists('detail_cpls');
    }
}
