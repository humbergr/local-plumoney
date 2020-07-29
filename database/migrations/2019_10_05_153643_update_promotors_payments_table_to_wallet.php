<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePromotorsPaymentsTableToWallet extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('promotor_payments', function (Blueprint $table) {
            $table->dropForeign(['promotor_id']);
            $table->dropColumn(['promotor_id', 'date_range_start', 'date_range_end']);
        });

        Schema::table('promotor_payments', function (Blueprint $table) {
            $table->integer('code_id')->unsigned();
        });

        Schema::table('promotor_payments', function($table) {
            $table->foreign('code_id')->references('id')->on('user_registration_codes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //Can't be undone
    }
}
