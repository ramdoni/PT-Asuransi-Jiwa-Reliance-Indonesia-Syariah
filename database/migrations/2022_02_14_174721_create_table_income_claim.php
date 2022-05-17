<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableIncomeClaim extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('income_claim', function (Blueprint $table) {
            $table->id();
            $table->integer('income_id')->nullable();
            $table->integer('amount')->nullable();
            $table->integer('outstanding')->nullable();
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
        Schema::dropIfExists('income_claim');
    }
}
