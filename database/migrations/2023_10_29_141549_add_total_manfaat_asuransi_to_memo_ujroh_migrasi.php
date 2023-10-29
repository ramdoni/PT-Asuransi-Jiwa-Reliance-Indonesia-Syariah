<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTotalManfaatAsuransiToMemoUjrohMigrasi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('memo_ujroh_migrasi', function (Blueprint $table) {
            $table->bigInteger('total_manfaat_asuransi')->nullable();
            $table->integer('extra_kontribusi')->nullable();
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
