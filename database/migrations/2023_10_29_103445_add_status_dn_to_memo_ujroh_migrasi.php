<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusDnToMemoUjrohMigrasi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('memo_ujroh_migrasi', function (Blueprint $table) {
            $table->integer('memo_ujroh_id')->nullable();
            $table->string('status_dn',70)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('memo_ujroh_migrasi', function (Blueprint $table) {
            //
        });
    }
}
