<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserExchangeTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_exchange_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->nullable();
            $table->integer('destination_account')->nullable();
            $table->string('sender_fiat')->nullable();
            $table->float('sender_fiat_amount', 255, 2)->default(0);
            $table->string('receiver_fiat')->nullable();
            $table->float('receiver_fiat_amount', 255, 2)->default(0);
            $table->string('payment_way')->nullable();
            $table->string('payment_source')->nullable();
            $table->integer('status')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->string('approved_by')->nullable();
            $table->json('metadata')->nullable();
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
        Schema::dropIfExists('user_exchange_transactions');
    }
}
