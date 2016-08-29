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
                $table->string('client_id', 40);
                $table->string('name', 40);
                $table->integer('author_id');
                $table->string('homepage_url');
                $table->string('logo_url');
                $table->string('description')->nullable();
                $table->string('scopes')->nullable();
                $table->string('secret', 40);
                $table->string('redirect_uri');
                $table->integer('status')->default(0); // -2：已下线； -1：已拒绝；０：待审核；１：已审核；２：开发中：３：已上线
                $table->engine = 'MyISAM';
                $table->timestamps();
                $table->unique('client_id');
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
        Schema::drop('apps');
    }
}
