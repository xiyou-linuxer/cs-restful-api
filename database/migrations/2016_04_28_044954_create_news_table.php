<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'news',
            function (Blueprint $table) {
                $table->increments('id');
                $table->integer('author_id');
                $table->integer('app_id');
                $table->integer('type')->default(0);
                $table->text('content');
                $table->char('topic', 64);
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
        Schema::drop('news');
    }
}
