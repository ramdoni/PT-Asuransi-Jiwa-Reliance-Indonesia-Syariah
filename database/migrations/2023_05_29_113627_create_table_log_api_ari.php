<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableLogApiAri extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_api_ari', function (Blueprint $table) {
            $table->id();
            $table->text('request')->nullable();
            $table->text('response')->nullable();
            $table->integer('kepesertaan_id')->nullable();
            $table->timestamps();
        });
        
        Schema::create('log_api_pan', function (Blueprint $table) {
            $table->id();
            $table->text('request')->nullable();
            $table->text('response')->nullable();
            $table->integer('kepesertaan_id')->nullable();
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
        Schema::dropIfExists('log_api_ari');
    }
}
