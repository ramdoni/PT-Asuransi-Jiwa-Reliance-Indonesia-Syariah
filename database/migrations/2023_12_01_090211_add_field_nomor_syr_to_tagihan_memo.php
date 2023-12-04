<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldNomorSyrToTagihanMemo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tagihan_soa', function (Blueprint $table) {
            $table->string('nomor_syr',150)->nullable();
            $table->string('nomor_cn_dn',150)->nullable();
            $table->date('tgl_jatuh_tempo')->nullable();
            $table->bigInteger('total_manfaat_asuransi')->nullable();
            $table->bigInteger('total_manfaat_asuransi_reas')->nullable();
            $table->bigInteger('kontribusi_gross')->nullable();
            $table->integer('ujroh')->nullable();
            $table->bigInteger('kontribusi_netto')->nullable();
            $table->bigInteger('refund')->nullable();
            $table->bigInteger('endorsement')->nullable();
            $table->bigInteger('klaim')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tagihan_memo', function (Blueprint $table) {
            //
        });
    }
}
