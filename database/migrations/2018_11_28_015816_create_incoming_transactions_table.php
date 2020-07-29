<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIncomingTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incoming_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('bank_name');
            $table->string('transaction_id');
            $table->float('amount', 155, 2);
            $table->string('currency');
            $table->longText('msg')->nullable();
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
        Schema::dropIfExists('incoming_transactions');
    }
}
