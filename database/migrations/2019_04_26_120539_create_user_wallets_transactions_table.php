<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserWalletsTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_wallets_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('wallet_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('type')->nullable();
            $table->integer('status')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->string('approved_by')->nullable();
            $table->float('amount', 255, 8)->default(0);
            $table->string('currency')->nullable();
            $table->integer('origin_foundation')->nullable();
            $table->integer('withdraw_mode')->nullable();
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
        Schema::dropIfExists('user_wallets_transactions');
    }
}
