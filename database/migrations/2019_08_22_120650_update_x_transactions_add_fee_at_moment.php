<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateXTransactionsAddFeeAtMoment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_exchange_transactions', function (Blueprint $table) {
            $table->smallInteger('fee_at_moment')->nullable();
        });

        Schema::table('user_wallets_transactions', function (Blueprint $table) {
            $table->smallInteger('fee_at_moment')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_exchange_transactions', function (Blueprint $table) {
            $table->dropColumn('fee_at_moment');
        });

        Schema::table('user_wallets_transactions', function (Blueprint $table) {
            $table->dropColumn('fee_at_moment');
        });
    }
}
