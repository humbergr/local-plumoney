<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddingNewFilesToUserWalletsTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_wallets_transactions', function (Blueprint $table) {
            $table->timestamp('failed_at')->nullable();
            $table->string('failed_by')->nullable();
            $table->string('tracking_id')->nullable();
            $table->string('payment_support')->nullable();
            $table->json('stripe_data')->nullable();
            $table->float('exchange_rate', 255, 2)->nullable();
            $table->integer('attended_by')->nullable();
            $table->timestamp('attended_at')->nullable();
            $table->integer('exchange_rate_id')->nullable();
            $table->integer('trader_id')->nullable();
            $table->json('trader_info')->nullable();
            $table->timestamp('refund_at')->nullable();
            $table->string('refund_by')->nullable();
            $table->text('notes')->nullable();
            $table->string('contact_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_wallets_transactions', function (Blueprint $table) {
            $table->dropColumn(
                'failed_at',
                'failed_by',
                'tracking_id',
                'payment_support',
                'stripe_data',
                'exchange_rate',
                'attended_by',
                'attended_at',
                'exchange_rate_id',
                'trader_id',
                'trader_info',
                'refund_at',
                'refund_by',
                'notes',
                'contact_id'
            );
        });
    }
}
