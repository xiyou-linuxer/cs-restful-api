<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'messages',
            function (Blueprint $table) {
                $table->increments('id');
                $table->integer('type');
                $table->integer('app_id');
                $table->integer('author_id');
                $table->text('receivers');
                $table->char('title', 128);
                $table->text('content');
                $table->integer('status');
                $table->engine = 'MyISAM';
                $table->timestamps();
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('messages');
    }
}
