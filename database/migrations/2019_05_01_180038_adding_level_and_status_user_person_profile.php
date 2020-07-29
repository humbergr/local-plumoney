<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddingLevelAndStatusUserPersonProfile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('user_person_profiles', function (Blueprint $table) {
          $table->integer('level')->default(1);
          $table->boolean('status')->default(false);
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
          $table->dropColumn('level');
          $table->dropColumn('status');
      });
    }
}
