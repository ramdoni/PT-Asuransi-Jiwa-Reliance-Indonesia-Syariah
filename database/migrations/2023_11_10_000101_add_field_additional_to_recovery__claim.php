<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldAdditionalToRecoveryClaim extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recovery_claim', function (Blueprint $table) {
            $table->date('tanggal_pengajuan')->nullable();
            $table->date('tanggal_efektif')->nullable();
            $table->date('tgl_jatuh_tempo')->nullable();
            $table->string('tujuan_pembayaran',100)->nullable();
            $table->string('nama_bank',50)->nullable();
            $table->string('no_rekening',80)->nullable();
            $table->string('no_peserta_awal',80)->nullable();
            $table->string('no_peserta_akhir',80)->nullable();
            $table->integer('user_created_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('recovery__claim', function (Blueprint $table) {
            //
        });
    }
}
