<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldHeadTeknikIdToRefund extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('refund', function (Blueprint $table) {
            $table->integer('user_head_teknik_id')->nullable();
            $table->integer('user_head_syariah_id')->nullable();
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
