<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldNoPesertaAwalToRefund extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('refund', function (Blueprint $table) {
            $table->string('no_peserta_awal',100)->nullable();
            $table->string('no_peserta_akhir',100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('refund', function (Blueprint $table) {
            //
        });
    }
}
