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
                $table->integer('type')->default(0); //0:用户; 1:应用
                $table->integer('author_id');
                $table->string('app_id', 40);
                $table->string('topic', 64);
                $table->string('link_url')->nullable();
                $table->text('content');
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
        Schema::drop('news');
    }
}
