<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldBankBookIdToIncomeTitipanPremi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('income_titipan_premi', function (Blueprint $table) {
            $table->integer('bank_book_id')->nullable();
        });

        Schema::table('income', function (Blueprint $table) {
            $table->integer('bank_book_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('income_titipan_premi', function (Blueprint $table) {
            //
        });
    }
}
