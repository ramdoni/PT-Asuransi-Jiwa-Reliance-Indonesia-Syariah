<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableReasEndorse extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reas_endorse', function (Blueprint $table) {
            $table->id();
            $table->integer('endorsement_id')->nullable();
            $table->boolean('status')->nullable();
            $table->integer('polis_id')->nullable();
            $table->date('tanggal_pengajuan')->nullable();
            $table->integer('reas_id')->nullable();
            $table->string('nomor',200)->nullable();
            $table->integer('total_peserta')->nullable();
            $table->bigInteger('total_manfaat_asuransi')->nullable();
            $table->bigInteger('total_kontribusi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reas_endorse');
    }
}
