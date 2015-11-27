<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeedbackTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_feedback_bug_info',function(Blueprint $table) {
            $table->increments('id');
            $table->text('content');
            $table->integer('user_id');
            $table->integer('status')->default(0);
            $table->string('method');
            $table->text('attr');
            $table->string('title');
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
        Schema::drop('app_feedback_bug_info');
    }
}
