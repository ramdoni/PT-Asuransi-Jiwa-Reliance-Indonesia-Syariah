<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldSoftdeleteToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dateTime('deleted_at')->nullable();
        });
        Schema::table('bank_accounts', function (Blueprint $table) {
            $table->dateTime('deleted_at')->nullable();
        });
        Schema::table('coas', function (Blueprint $table) {
            $table->dateTime('deleted_at')->nullable();
        });
        Schema::table('coa_groups', function (Blueprint $table) {
            $table->dateTime('deleted_at')->nullable();
        });
        Schema::table('code_cashflows', function (Blueprint $table) {
            $table->dateTime('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
