<?php

use App\Models\DosenAdmin;
use App\Models\MataKuliah;
use App\Models\TahunAjaran;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesmksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rolesmks', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(TahunAjaran::class);
            $table->foreignIdFor(MataKuliah::class);
            $table->foreignIdFor(DosenAdmin::class);
            $table->enum('semester', ['1', '2']);
            $table->enum('status', ['koordinator', 'pengampu']);
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
        Schema::dropIfExists('rolesmks');
    }
}
