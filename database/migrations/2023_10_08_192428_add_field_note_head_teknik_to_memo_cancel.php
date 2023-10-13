<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldNoteHeadTeknikToMemoCancel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('memo_cancel', function (Blueprint $table) {
            $table->dateTime('head_teknik_submitted')->nullable();
            $table->text('head_teknik_note')->nullable();
            $table->dateTime('head_syariah_submitted')->nullable();
            $table->text('head_syariah_note')->nullable();
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
