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
                $table->integer('message_id');
                $table->integer('type')->default(1);//0:用户; 1:应用
                $table->string('app_id', 40);
                $table->integer('author_id');
                $table->integer('receiver_id')->default(0);
                $table->string('all_receiver_ids')->nullable();
                $table->string('title', 128);
                $table->text('content');
                $table->integer('status')->default(0);//0 草稿; 1 已发送，未读；2已读
                $table->index('message_id');
                $table->engine = 'MyISAM';
                $table->timestamps();
                $table->softDeletes();
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
