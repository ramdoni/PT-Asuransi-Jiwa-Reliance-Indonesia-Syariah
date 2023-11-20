<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableEndors extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('endorsement', function (Blueprint $table) {
            $table->id();
            $table->integer('polis_id')->nullable();
            $table->date('tanggal_pengajuan')->nullable();
            $table->boolean('jenis_pengajuan')->nullable();
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
        Schema::dropIfExists('endorsement');
    }
}
