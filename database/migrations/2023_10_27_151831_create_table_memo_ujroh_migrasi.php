<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableMemoUjrohMigrasi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('memo_ujroh_migrasi', function (Blueprint $table) {
            $table->id();
            $table->string('no_debit_note')->nullable();
            $table->bigInteger('kontribusi_gross')->nullable();
            $table->bigInteger('kontribusi_nett')->nullable();
            $table->date('tanggal_bayar')->nullable();
            $table->string('maintenance_persen',11)->nullable();
            $table->bigInteger('maintenance')->nullable();
            $table->string('agen_penutup_persen',11)->nullable();
            $table->bigInteger('agen_penutup')->nullable();
            $table->string('admin_agency_persen',11)->nullable();
            $table->bigInteger('admin_agency')->nullable();
            $table->string('handling_fee_persen',11)->nullable();
            $table->bigInteger('handling_fee')->nullable();
            $table->string('referal_fee_persen',11)->nullable();
            $table->bigInteger('referal_fee')->nullable();
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
        Schema::dropIfExists('memo_ujroh_migrasi');
    }
}
