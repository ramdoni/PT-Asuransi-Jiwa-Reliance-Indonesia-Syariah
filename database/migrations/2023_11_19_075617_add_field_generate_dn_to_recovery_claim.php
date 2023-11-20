<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldGenerateDnToRecoveryClaim extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recovery_claim', function (Blueprint $table) {
            $table->string('rekon_dn')->nullable();
            $table->boolean('rekon_status')->default(0)->nullable();
            $table->dateTime('rekon_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('recovery_claim', function (Blueprint $table) {
            //
        });
    }
}
