<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldTanggalMulaiToMemoCancelKepesertaan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('memo_cancel_peserta', function (Blueprint $table) {
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_akhir')->nullable();
            $table->integer('masa_bulan')->nullable();
            $table->integer('basic')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('memo_cancel_kepesertaan', function (Blueprint $table) {
            //
        });
    }
}
