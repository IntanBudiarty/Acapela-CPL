<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRumusanAkhirMkCplsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rumusan_akhir_mk_cpls', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rumusan_akhir_mk_id');
            $table->unsignedBigInteger('cpl_id');
            $table->timestamps();

            // Foreign Key Constraints
            $table->foreign('rumusan_akhir_mk_id')->references('id')->on('rumusan_akhir_mk')->onDelete('cascade');
            $table->foreign('cpl_id')->references('id')->on('cpls')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rumusan_akhir_mk_cpls');
    }
}
