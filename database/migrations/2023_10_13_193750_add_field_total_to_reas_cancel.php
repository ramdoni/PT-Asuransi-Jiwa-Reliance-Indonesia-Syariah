<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldTotalToReasCancel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reas_cancel', function (Blueprint $table) {
            $table->integer('total_peserta')->nullable();
            $table->bigInteger('total_manfaat_asuransi')->nullable();
            $table->bigInteger('total_kontribusi')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reas_cancel', function (Blueprint $table) {
            //
        });
    }
}
