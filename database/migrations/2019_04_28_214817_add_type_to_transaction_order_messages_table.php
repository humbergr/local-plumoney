<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypeToTransactionOrderMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transaction_order_messages', function (Blueprint $table) {
            $table->integer('type')->default(1);
            $table->json('json_data')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transaction_order_messages', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->dropColumn('json_data');
        });
    }
}
