<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldUserApprovalToMemoUjroh extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('memo_ujroh', function (Blueprint $table) {
            $table->integer('user_created_id')->nullable();
            $table->integer('user_teknik_id')->nullable();
            $table->dateTime('user_teknik_approved_date')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('memo_ujroh', function (Blueprint $table) {
            //
        });
    }
}
