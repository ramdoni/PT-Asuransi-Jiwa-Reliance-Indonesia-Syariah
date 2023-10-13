<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldTotalKontribusiTambahanToMemoCancel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('memo_cancel', function (Blueprint $table) {
            $table->bigInteger('total_kontribusi_tambahan')->nullable();
            $table->bigInteger('total_potongan_langsung')->nullable();
            $table->integer('total_ujroh_brokerage')->nullable();
            $table->integer('total_ppn')->nullable();
            $table->integer('total_pph')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('memo_cancel', function (Blueprint $table) {
            //
        });
    }
}
