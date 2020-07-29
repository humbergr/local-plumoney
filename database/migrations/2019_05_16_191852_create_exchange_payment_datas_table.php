<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExchangePaymentDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exchange_payment_datas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('exchange_id');
            $table->string('bank_name');
            $table->string('deposit_number');
            $table->string('account_number');
            $table->timestamp('deposit_date');
            $table->string('attachment_name')->nullable();
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
        Schema::dropIfExists('exchange_payment_datas');
    }
}
