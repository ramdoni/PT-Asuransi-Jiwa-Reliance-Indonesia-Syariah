<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldFeeBaseBrokerageToMemoCancel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('memo_cancel', function (Blueprint $table) {
            $table->decimal('brokerage_ujrah_persen',12,2)->nullable();
            $table->decimal('fee_base_brokerage',12,2)->nullable();
            $table->decimal('potong_langsung')->nullable();
            $table->decimal('potong_langsung_amount',12,2)->nullable();
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
