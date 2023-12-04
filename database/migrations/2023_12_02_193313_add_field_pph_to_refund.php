<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldPphToRefund extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('refund', function (Blueprint $table) {
            $table->string('pph',10)->nullable();
            $table->integer('pph_amount')->nullable();
            $table->string('ppn',10)->nullable();
            $table->integer('ppn_amount')->nullable();

            $table->string('brokerage_ujrah_persen',10)->nullable();
            $table->integer('brokerage_ujrah')->nullable();
            
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
