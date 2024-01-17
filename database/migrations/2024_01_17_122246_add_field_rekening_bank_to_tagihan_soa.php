<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldRekeningBankToTagihanSoa extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tagihan_soa', function (Blueprint $table) {
            $table->string('bank_name',100)->nullable();
            $table->string('bank_no_rekening',50)->nullable();
            $table->string('bank_owner',100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tagihan_soa', function (Blueprint $table) {
            //
        });
    }
}
