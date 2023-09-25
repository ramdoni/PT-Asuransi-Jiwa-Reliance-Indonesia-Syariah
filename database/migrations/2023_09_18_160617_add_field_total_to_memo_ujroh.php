<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldTotalToMemoUjroh extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('memo_ujroh', function (Blueprint $table) {
            $table->integer('total_maintenance')->nullable();
            $table->integer('total_agen_penutup')->nullable();
            $table->integer('total_admin_agency')->nullable();
            $table->integer('total_ujroh_handling_fee_broker')->nullable();
            $table->integer('total_referal_fee')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('memo_ujroh', function (Blueprint $table) {
            //
        });
    }
}
