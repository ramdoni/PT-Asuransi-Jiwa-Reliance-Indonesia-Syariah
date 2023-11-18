<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableRecoveryClaimPayment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recovery_claim_payments', function (Blueprint $table) {
            $table->id();
            $table->integer('recovery_claim_id')->nullable();
            $table->integer('payment_amount')->nullable();
            $table->date('payment_date')->nullable();
            $table->text('payment_file')->nullable();
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
        Schema::dropIfExists('recovery_claim_payment');
    }
}
