<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldTotalKontribusiGrossToEndorsement extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('endorsement', function (Blueprint $table) {
            $table->bigInteger('total_kontribusi_gross')->nullable();
            $table->integer('total_potongan_langsung')->nullable();
            $table->integer('total_kontribusi_tambahan')->nullable();
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
        Schema::table('endorsement', function (Blueprint $table) {
            //
        });
    }
}
