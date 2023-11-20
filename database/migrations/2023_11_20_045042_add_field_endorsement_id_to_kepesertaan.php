<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldEndorsementIdToKepesertaan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kepesertaan', function (Blueprint $table) {
            $table->unsignedBigInteger('endorsement_id')->nullable();
            $table->foreign('endorsement_id')->references('id')->on('endorsement')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kepesertaan', function (Blueprint $table) {
            //
        });
    }
}
