<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableEndorsementPeserta extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('endorsement_pesertas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('endorsement_id')->nullable();
            $table->foreign('endorsement_id')->references('id')->on('endorsement')->onDelete('cascade');
            $table->text('before_data')->nullable();
            $table->text('after_data')->nullable();
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
        Schema::dropIfExists('endorse_pesertas');
    }
}
