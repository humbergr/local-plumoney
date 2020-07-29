<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateErrorTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('error_transactions', function (Blueprint $table) {
          $table->increments('id');
          $table->string('transaction_id');
          $table->float('amount_btc', 155, 8);
          $table->json('json_data')->nullable();
          $table->boolean('was_solved')->default(false);
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
        Schema::dropIfExists('error_transactions');
    }
}
