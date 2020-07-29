<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class WalletTransactionsCache extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallet_transactions_received_caches', function (Blueprint $table) {
            $table->string('fingerprint', 255);
            $table->integer('contact_id')->nullable();
            $table->string('txid', 255)->nullable();
            $table->string('account_name', 255);
            $table->json('json_data');
            $table->smallInteger('status')->default(0);
            $table->dateTime('anchor_date_localbtc')->nullable();
            $table->dateTime('anchor_date_est')->nullable();
            $table->timestamps();

            $table->primary('fingerprint');
        });

        Schema::create('wallet_transactions_sent_caches', function (Blueprint $table) {
            $table->string('fingerprint', 255);
            $table->integer('contact_id')->nullable();
            $table->string('txid', 255)->nullable();
            $table->string('account_name', 255);
            $table->json('json_data');
            $table->float('fee', 255, 8)->nullable();
            $table->smallInteger('status')->default(0);
            $table->dateTime('anchor_date_localbtc')->nullable();
            $table->dateTime('anchor_date_est')->nullable();
            $table->timestamps();

            $table->primary('fingerprint');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
