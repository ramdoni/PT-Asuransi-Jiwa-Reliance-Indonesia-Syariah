<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldPenerimaPembayaranToMemoUjroh extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('memo_ujroh', function (Blueprint $table) {

            $table->string('maintenance',25)->nullable();
            $table->string('maintenance_penerima',100)->nullable();
            $table->string('maintenance_nama_bank',100)->nullable();
            $table->string('maintenance_no_rekening',100)->nullable();

            $table->string('admin_agency',25)->nullable();
            $table->string('admin_agency_penerima',100)->nullable();
            $table->string('admin_agency_nama_bank',100)->nullable();
            $table->string('admin_agency_no_rekening',100)->nullable();
            
            $table->string('agen_penutup',25)->nullable();
            $table->string('agen_penutup_penerima',100)->nullable();
            $table->string('agen_penutup_nama_bank',100)->nullable();
            $table->string('agen_penutup_no_rekening',100)->nullable();
            
            $table->string('ujroh_handling_fee_broker',25)->nullable();
            $table->string('ujroh_handling_fee_broker_penerima',100)->nullable();
            $table->string('ujroh_handling_fee_broker_nama_bank',100)->nullable();
            $table->string('ujroh_handling_fee_broker_no_rekening',100)->nullable();

            $table->string('referal_fee',25)->nullable();
            $table->string('referal_fee_penerima',100)->nullable();
            $table->string('referal_fee_nama_bank',100)->nullable();
            $table->string('referal_fee_no_rekening',100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('memo_ujroh', function (Blueprint $table) {
            //
        });
    }
}
