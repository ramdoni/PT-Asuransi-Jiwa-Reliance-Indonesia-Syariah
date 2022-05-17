<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableBankBookPairing extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_book_pairing', function (Blueprint $table) {
            $table->id();
            $table->integer('bank_book_id')->nullable();
            $table->string('transaction_table',50)->nullable();
            $table->integer('transaction_id')->nullable();
            $table->integer('amount')->nullable();
            $table->timestamps();

            $table->index(['bank_book_id','transaction_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bank_book_pairing');
    }
}
