<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableBankBook extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_books', function (Blueprint $table) {
            $table->id();
            $table->string('no_voucher',150)->nullable();
            $table->integer('from_bank_id')->nullable();
            $table->integer('to_bank_id')->nullable();
            $table->integer('amount')->nullable();
            $table->text('note')->nullable();
            $table->boolean('status')->default(0)->nullable();
            $table->timestamps();

            $table->index(['from_bank_id','to_bank_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bank_books');
    }
}
