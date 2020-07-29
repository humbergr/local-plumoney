<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePromotorPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promotor_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('paid_by')->unsigned();
            $table->integer('promotor_id')->unsigned();
            $table->integer('payment_total');
            $table->string('currency', 255);
            $table->datetime('payment_date');
            $table->datetime('date_range_start');
            $table->datetime('date_range_end');
        });

        Schema::table('promotor_payments', function($table) {
            $table->foreign('promotor_id')->references('id')->on('users');
            $table->foreign('paid_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('promotor_payments');
    }
}
