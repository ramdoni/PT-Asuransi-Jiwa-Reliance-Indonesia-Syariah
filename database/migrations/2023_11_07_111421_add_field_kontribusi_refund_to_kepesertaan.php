<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldKontribusiRefundToKepesertaan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kepesertaan', function (Blueprint $table) {
            $table->bigInteger('refund_kontribusi')->nullable();
            $table->smallInteger('refund_sisa_masa_asuransi')->nullable();
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
