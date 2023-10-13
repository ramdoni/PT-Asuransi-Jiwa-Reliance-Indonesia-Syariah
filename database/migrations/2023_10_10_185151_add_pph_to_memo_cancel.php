<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPphToMemoCancel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('memo_cancel', function (Blueprint $table) {
            $table->decimal('pph',8,2)->nullable();
            $table->decimal('pph_amount',8,2)->nullable();
            $table->decimal('ppn',8,2)->nullable();
            $table->decimal('ppn_amount',8,2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('memo_cancel', function (Blueprint $table) {
            //
        });
    }
}
