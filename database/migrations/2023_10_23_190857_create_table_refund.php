<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableRefund extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('refund', function (Blueprint $table) {
            $table->id();
            $table->string('nomor',200)->nullable();
            $table->integer('polis_id')->nullable();
            $table->integer('total_peserta')->nullable();
            $table->bigInteger('total_manfaat_asuransi')->nullable();
            $table->bigInteger('total_kontribusi_gross')->nullable();
            $table->integer('total_kontribusi_tambahan')->nullable();
            $table->integer('total_potongan_langsung')->nullable();
            $table->integer('total_ujroh_brokerage')->nullable();
            $table->integer('total_ppn')->nullable();
            $table->integer('total_pph')->nullable();
            $table->bigInteger('total_kontribusi')->nullable();
            $table->date('head_teknik_submitted')->nullable();
            $table->text('head_teknik_note')->nullable();
            $table->date('head_syariah_submitted')->nullable();
            $table->text('head_syariah_note')->nullable();
            $table->boolean('status')->default(0)->nullable();
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
        Schema::dropIfExists('refund');
    }
}
