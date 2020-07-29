<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddApprovedByAtUserProfile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_person_profiles', function (Blueprint $table) {
            $table->string('id_approved')->nullable();
            $table->text('approved_by_gps')->nullable();

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
            $table->dropColumn('id_approved');
            $table->text('approved_by_gps')->nullable();

        });
    }
}
