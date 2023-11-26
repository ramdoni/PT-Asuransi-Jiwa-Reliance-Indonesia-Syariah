<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldPotongLangungEtcToEndorsement extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('endorsement', function (Blueprint $table) {
            $table->integer('em')->nullable();
            $table->integer('ek')->nullable();
            $table->string('brokerage_ujrah_persen',10)->nullable();
            $table->decimal('brokerage_ujrah',19,2)->nullable();
            $table->decimal('pph',19,2)->nullable();
            $table->string('pph_persen',12)->nullable();
            $table->decimal('ppn',19,2)->nullable();
            $table->string('ppn_persen',12)->nullable();

            $table->integer('em_perubahan')->nullable();
            $table->integer('ek_perubahan')->nullable();
            $table->string('brokerage_ujrah_persen_perubahan',10)->nullable();
            $table->decimal('brokerage_ujrah_perubahan',19,2)->nullable();
            $table->decimal('pph_perubahan',19,2)->nullable();
            $table->string('pph_persen_perubahan',12)->nullable();
            $table->decimal('ppn_perubahan',19,2)->nullable();
            $table->string('ppn_persen_perubahan',12)->nullable();


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
