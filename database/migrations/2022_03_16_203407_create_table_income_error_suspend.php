<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableIncomeErrorSuspend extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('income_error_suspends', function (Blueprint $table) {
            $table->id();
            $table->integer('income_id')->nullable();
            $table->integer('amount')->nullable();
            $table->string('description')->nullable();
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
        Schema::dropIfExists('income_error_suspend');
    }
}
