<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldPolisMemoUjrohToPolis extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('polis', function (Blueprint $table) {
            $table->string('maintenance_penerima',100)->nullable();
            $table->string('maintenance_nama_bank',100)->nullable();
            $table->string('maintenance_no_rekening',100)->nullable();

            $table->string('admin_agency_penerima',100)->nullable();
            $table->string('admin_agency_nama_bank',100)->nullable();
            $table->string('admin_agency_no_rekening',100)->nullable();
            
            $table->string('agen_penutup_penerima',100)->nullable();
            $table->string('agen_penutup_nama_bank',100)->nullable();
            $table->string('agen_penutup_no_rekening',100)->nullable();
            
            $table->string('ujroh_handling_fee_broker_penerima',100)->nullable();
            $table->string('ujroh_handling_fee_broker_nama_bank',100)->nullable();
            $table->string('ujroh_handling_fee_broker_no_rekening',100)->nullable();

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
        Schema::table('polis', function (Blueprint $table) {
            //
        });
    }
}
