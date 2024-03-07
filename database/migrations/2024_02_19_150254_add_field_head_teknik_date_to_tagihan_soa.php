<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldHeadTeknikDateToTagihanSoa extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tagihan_soa', function (Blueprint $table) {
            $table->dateTime('head_teknik_submitted')->nullable();
            $table->dateTime('head_syariah_submitted')->nullable();
        });

        Schema::table('reas', function (Blueprint $table) {
            $table->integer('tagihan_soa_id')->nullable();
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
