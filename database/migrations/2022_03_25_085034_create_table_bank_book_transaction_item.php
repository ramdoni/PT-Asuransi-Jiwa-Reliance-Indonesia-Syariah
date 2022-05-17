<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableBankBookTransactionItem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_book_transaction_item', function (Blueprint $table) {
            $table->id();
            $table->integer('bank_book_transaction_id')->nullable();
            $table->integer('amount')->nullable();
            $table->string('type',50)->nullable();
            $table->integer('transaction_id')->nullable();
            $table->string('dn',200)->nullable();
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index(['bank_book_transaction_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bank_book_transaction_item');
    }
}
