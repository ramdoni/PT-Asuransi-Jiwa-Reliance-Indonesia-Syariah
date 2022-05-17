<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableIncomeSettle extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('income_settle', function (Blueprint $table) {
            $table->id();
            $table->integer('income_id')->nullable();
            $table->integer('amount')->nullable();
            $table->boolean('type')->nullable();
            $table->integer('transaction_id')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index(['income_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('income_settle');
    }
}
