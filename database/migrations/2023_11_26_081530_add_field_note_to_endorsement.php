<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldNoteToEndorsement extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('endorsement', function (Blueprint $table) {
            $table->text('head_teknik_note')->nullable();
            $table->integer('head_teknik_id')->nullable();
            $table->date('head_teknik_date')->nullable();

            $table->text('head_syariah_note')->nullable();
            $table->integer('head_syariah_id')->nullable();
            $table->date('head_syariah_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('endorsement', function (Blueprint $table) {
            //
        });
    }
}
