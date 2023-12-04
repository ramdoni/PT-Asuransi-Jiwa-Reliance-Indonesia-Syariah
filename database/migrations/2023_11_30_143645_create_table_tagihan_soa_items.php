<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableTagihanSoaItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tagihan_soa_klaim', function (Blueprint $table) {
            $table->id();
            $table->integer('tagihan_soa_id')->nullable();
            $table->text('raw_data')->nullable();
            $table->integer('klaim_id')->nullable();
            $table->timestamps();
        });

        Schema::create('tagihan_soa_pengajuan', function (Blueprint $table) {
            $table->id();
            $table->integer('tagihan_soa_id')->nullable();
            $table->text('raw_data')->nullable();
            $table->integer('pengajuan_id')->nullable();
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
        Schema::dropIfExists('tagihan_soa');
    }
}
