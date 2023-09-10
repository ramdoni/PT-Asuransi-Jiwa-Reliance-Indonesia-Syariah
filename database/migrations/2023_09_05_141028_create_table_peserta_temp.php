<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTablePesertaTemp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {   
        Schema::create('kepesertaan_temp', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_akhir')->nullable();
            $table->integer('polis_id')->nullable();
            $table->integer('pengajuan_id')->nullable();
            $table->string('cab',150)->nullable();
            $table->string('no_akad_kredit',200)->nullable();
            $table->string('nomor_rekening',100)->nullable();
            $table->string('nama',150)->nullable();
            $table->string('no_ktp',150)->nullable();
            $table->string('npwp',150)->nullable();
            $table->string('jenis_kelamin',50)->nullable();
            $table->string('pekerjaan',100)->nullable();
            $table->integer('masa_bulan')->nullable();
            $table->string('jenis_pembiayaan',100)->nullable();
            $table->string('jenis_pengajuan',100)->nullable();
            $table->bigInteger('basic')->nullable();
            $table->string('bunga',10)->nullable();
            $table->integer('kontribusi')->nullable();
            $table->string('benefit',100)->nullable();
            $table->string('packet',100)->nullable();
            $table->integer('usia')->nullable();
            $table->string('rate',12)->nullable();
            $table->integer('ari_kontribusi')->nullable();
            $table->integer('ari_rate')->nullable();
            $table->string('ul',50)->nullable();
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
        Schema::dropIfExists('kepesertaan_temp');
    }
}
