<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRumusanIdToNilaisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::table('nilais', function (Blueprint $table) {
        $table->foreign('rumusan_id')
              ->references('id')
              ->on('rumusan_akhir_mk')
              ->onDelete('cascade');
    });
    
}

public function down()
{
    Schema::table('nilais', function (Blueprint $table) {
        $table->dropForeign(['rumusan_id']);
        $table->dropColumn('rumusan_id');
    });
}

}
