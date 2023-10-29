<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTanggalDnToMemoUjrohMigrasi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('memo_ujroh_migrasi', function (Blueprint $table) {
            $table->date('tanggal_dn')->nullable();
            $table->string('no_peserta_awal',100)->nullable();
            $table->string('no_peserta_akhir',100)->nullable();
        });

        Schema::table('memo_ujroh', function (Blueprint $table) {
            $table->integer('extra_kontribusi')->nullable();
            $table->string('discount',12)->nullable();
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
