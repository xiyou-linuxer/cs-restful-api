<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'apps',
            function (Blueprint $table) {
                $table->increments('id');
                $table->char('name', 32);
                $table->char('description', 255)->nullable();
                $table->char('key', 128);
                $table->integer('status');
                $table->char('redirect_url', 255)->nullable();
                $table->text('permissions')->nullable();
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
        Schema::drop('apps');
    }
}
