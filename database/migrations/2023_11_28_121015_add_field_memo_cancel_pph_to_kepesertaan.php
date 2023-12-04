<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldMemoCancelPphToKepesertaan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kepesertaan', function (Blueprint $table) {
            $table->integer('cancel_pph')->nullable();
            $table->integer('cancel_ppn')->nullable();
            $table->integer('cancel_potong_langsung')->nullable();
            $table->integer('cancel_fee_base')->nullable();
            $table->integer('cancel_kontribusi_netto')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kepesertaan', function (Blueprint $table) {
            //
        });
    }
}
