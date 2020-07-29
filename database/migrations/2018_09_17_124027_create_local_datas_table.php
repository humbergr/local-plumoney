<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLocalDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('local_datas', function (Blueprint $table) {
            $table->increments('id');
            $table->float('bs1h', 255, 2);
            $table->float('bs6h', 255, 2);
            $table->float('bs12h', 255, 2);
            $table->float('bs24h', 255, 2);
            $table->float('us1h', 255, 2);
            $table->float('us6h', 255, 2);
            $table->float('us12h', 255, 2);
            $table->float('us24h', 255, 2);
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
        Schema::dropIfExists('local_datas');
    }
}
