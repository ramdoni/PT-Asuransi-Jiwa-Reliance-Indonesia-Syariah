<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableMemoCancelPeserta extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('memo_cancel_peserta', function (Blueprint $table) {
            $table->id();
            $table->integer('memo_cancel_id')->nullable();
            $table->integer('kepesertaan_id')->nullable();
            $table->string('nama',100)->nullable();
            $table->string('no_peserta',100)->nullable();
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
        Schema::dropIfExists('memo_cancel_peserta');
    }
}
