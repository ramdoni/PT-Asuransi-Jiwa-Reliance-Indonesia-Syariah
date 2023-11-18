<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldReasFileToRecoveryClaim extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recovery_claim', function (Blueprint $table) {
            $table->text('reas_file_jawaban')->nullable();
            $table->text('reas_file_penerimaan')->nullable();
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
