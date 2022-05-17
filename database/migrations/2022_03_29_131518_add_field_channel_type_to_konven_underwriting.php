<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldChannelTypeToKonvenUnderwriting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('konven_underwriting', function (Blueprint $table) {
            $table->string('channel_type',50)->nullable();
            $table->string('channel_name',100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('konven_underwriting', function (Blueprint $table) {
            //
        });
    }
}
