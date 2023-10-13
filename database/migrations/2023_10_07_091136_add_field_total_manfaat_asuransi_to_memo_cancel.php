<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldTotalManfaatAsuransiToMemoCancel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('memo_cancel', function (Blueprint $table) {
            $table->bigInteger('total_manfaat_asuransi')->nullable();
            $table->bigInteger('total_kontribusi_gross')->nullable();
            $table->bigInteger('total_nilai_manfaat')->nullable();
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
