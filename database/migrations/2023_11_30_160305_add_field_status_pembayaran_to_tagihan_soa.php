<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldStatusPembayaranToTagihanSoa extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tagihan_soa', function (Blueprint $table) {
            $table->integer('reasuradur_id')->nullable();
            $table->boolean('status_pembayaran')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tagihan_soa', function (Blueprint $table) {
            //
        });
    }
}
