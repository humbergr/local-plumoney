<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUserRegCodeUsesAddPayed extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_registration_code_uses', function (Blueprint $table) {
            $table->integer('is_payed')->nullable();
            $table->timestamp('payed_at')->nullable();
            $table->string('payed_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_registration_codes', function (Blueprint $table) {
            $table->dropColumn(['is_payed', 'payed_at', 'payed_by']);
        });
    }
}
