<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableMemoUjrah extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('memo_ujroh', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_pengajuan')->nullable();
            $table->string('nomor',200)->nullable();
            $table->string('perihal',255)->nullable();
            $table->integer('polis_id')->nullable();
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
        Schema::dropIfExists('memo_ujroh');
    }
}
