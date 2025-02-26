<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCpmkMataKuliahTable extends Migration
{
    public function up()
    {
        Schema::create('cpmk_mata_kuliah', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cpmk_id')->constrained('cpmks')->onDelete('cascade');
            $table->foreignId('mata_kuliah_id')->constrained('mata_kuliahs')->onDelete('cascade');
            $table->timestamps();
        });        
    }

    public function down()
    {
        Schema::dropIfExists('cpmk_mata_kuliah');
    }
}
