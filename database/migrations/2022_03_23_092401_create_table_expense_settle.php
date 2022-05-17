<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableExpenseSettle extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expense_settle', function (Blueprint $table) {
            $table->id();
            $table->integer('expense_id')->nullable();
            $table->integer('amount')->nullable();
            $table->boolean('type')->nullable();
            $table->integer('transaction_id')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index(['expense_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('expense_settle');
    }
}
