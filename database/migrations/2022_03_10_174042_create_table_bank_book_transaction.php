<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableBankBookTransaction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_book_transaction', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('amount')->nullable();
            $table->timestamps();
        });
        
        Schema::create('error_suspense', function (Blueprint $table) {
            $table->id();
            $table->integer('bank_book_transaction_id')->nullable();
            $table->integer('amount')->nullable();
            $table->text('note')->nullable();
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
        Schema::dropIfExists('bank_book_transaction');
    }
}
