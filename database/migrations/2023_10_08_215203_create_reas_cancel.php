<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReasCancel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reas_cancel', function (Blueprint $table) {
            $table->id();
            $table->string('nomor')->nullable();
            $table->boolean('status')->default(0)->nullable();
            $table->integer('polis_id')->nullable();
            $table->date('tanggal_pengajuan')->nullable();
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
        Schema::dropIfExists('reas_cancel');
    }
}
