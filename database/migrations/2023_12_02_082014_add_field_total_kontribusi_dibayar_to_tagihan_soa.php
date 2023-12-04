<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldTotalKontribusiDibayarToTagihanSoa extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tagihan_soa', function (Blueprint $table) {
            $table->bigInteger('total_kontribusi_dibayar')->nullable();
            $table->boolean('is_cn')->default(0)->nullable();
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
