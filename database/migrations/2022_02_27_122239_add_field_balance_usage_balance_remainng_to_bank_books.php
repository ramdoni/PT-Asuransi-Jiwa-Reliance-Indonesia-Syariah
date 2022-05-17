<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldBalanceUsageBalanceRemainngToBankBooks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bank_books', function (Blueprint $table) {
            $table->integer('balance_usage')->nullable();
            $table->integer('balance_remain')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bank_books', function (Blueprint $table) {
            //
        });
    }
}
