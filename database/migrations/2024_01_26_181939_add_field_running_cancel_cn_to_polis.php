<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldRunningCancelCnToPolis extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('polis', function (Blueprint $table) {
            $table->integer('running_number_endorse_cn_dn')->default(0)->nullable();
            $table->integer('running_number_cancel_cn')->default(0)->nullable();
            $table->integer('running_number_refund_cn')->default(0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('polis', function (Blueprint $table) {
            //
        });
    }
}
