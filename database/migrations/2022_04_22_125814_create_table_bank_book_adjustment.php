<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableBankBookAdjustment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_book_adjustment', function (Blueprint $table) {
            $table->id();
            $table->integer('bank_book_id')->nullable();
            $table->integer('transaction_id')->nullable();
            $table->string('transaction_table',20)->nullable();
            $table->integer('amount')->nullable();
            $table->string('voucher_number',100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bank_book_adjustment');
    }
}
