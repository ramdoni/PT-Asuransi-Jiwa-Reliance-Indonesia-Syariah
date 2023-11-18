<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldKirimReasToRecoveryClaim extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recovery_claim', function (Blueprint $table) {
            $table->date('reas_tanggal_kirim')->nullable();
            $table->date('reas_tanggal_jawaban')->nullable();
            $table->text('reas_note_jawaban')->nullable();
            $table->date('reas_tanggal_penerimaan')->nullable();
            $table->text('reas_note_penerimaan')->nullable();
            $table->boolean('reas_status')->default(0)->nullable();
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
