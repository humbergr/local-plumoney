<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ticket_number');
            $table->integer('user_id')->unsigned();
            $table->integer('dept_id')->unsigned()->nullable();
            $table->integer('group_id')->unsigned()->nullable();
            $table->integer('priority_id')->unsigned();
            $table->string('title');
            $table->integer('status');
            $table->integer('rating')->nullable();
            $table->integer('ratingreply')->nullable();
            $table->integer('assigned_to')->unsigned()->nullable();
            $table->integer('source')->nullable();
            $table->integer('is_answered')->default(0);
            $table->integer('is_deleted')->default(0);
            $table->integer('is_transferred')->default(0);
            $table->dateTime('transferred_at')->nullable();
            $table->dateTime('duedate')->nullable();
            $table->dateTime('closed_at')->nullable();
            $table->dateTime('last_message_at')->nullable();
            $table->dateTime('last_response_at')->nullable();
            $table->integer('is_registered');
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
        Schema::dropIfExists('tickets');
    }
}
