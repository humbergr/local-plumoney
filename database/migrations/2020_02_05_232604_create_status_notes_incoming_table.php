<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatusNotesIncomingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('status_notes_incoming', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('status')->nullable();
            $table->integer('subject_id')->nullable();
            $table->integer('client_id')->nullable();
            $table->string('msg')->nullable();
            $table->integer('trader_id')->nullable();
            $table->integer('transaction_id')->nullable();
            $table->string('reply')->nullable();
            $table->string('ip')->nullable();
            $table->string('ip_reply')->nullable();
            $table->string('reply_file')->nullable();
            $table->integer('is_reply')->nullable();
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
        Schema::dropIfExists('status_notes_incoming');
    }
}
