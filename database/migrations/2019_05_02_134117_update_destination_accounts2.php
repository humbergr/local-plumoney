<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateDestinationAccounts2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('destination_accounts', function (Blueprint $table) {
            $table->dropColumn('account_type');
        });
        Schema::table('destination_accounts', function (Blueprint $table) {
            $table->timestamp('birthday')->nullable();
            $table->smallInteger('id_type')->nullable();
            $table->smallInteger('account_type')->nullable();
            $table->smallInteger('type')->nullable();
            $table->timestamp('id_origin_date')->nullable();
            $table->timestamp('id_end_date')->nullable();
            $table->string('id_origin_country')->nullable();
            $table->string('id_origin_state')->nullable();
            $table->string('state')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('city')->nullable();
            $table->string('street')->nullable();
            $table->string('aba_number')->nullable();
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
