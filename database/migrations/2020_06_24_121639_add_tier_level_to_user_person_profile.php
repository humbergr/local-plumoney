<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTierLevelToUserPersonProfile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_person_profiles', function (Blueprint $table) {
            $table->integer('tier_level')->default(0);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_person_profiles', function (Blueprint $table) {
            $table->dropColumn('tier_level');

        });
    }
}
