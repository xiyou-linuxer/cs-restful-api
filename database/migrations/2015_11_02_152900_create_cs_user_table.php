<?php
/**
 * Migtation to create cs_user table
 *
 * PHP version 5.6
 *
 * @category PHP
 * @package  PHP_Laveral
 * @author   teddyliao <sxliao@foxmail.com>
 * @license  http://xiyoulinux.org BSD Licence
 * @link     http://cs.xiyoulinux.org
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
/**
 * Descrip the migration class for CsUser
 *
 * PHP version 5.6
 *
 * @category PHP
 * @package  PHP_Laveral
 * @author   teddyliao <sxliao@foxmail.com>
 * @license  http://xiyoulinux.org BSD Licence
 * @link     http://cs.xiyoulinux.org
 */

class CreateCsUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'cs_user', 
            function (Blueprint $table) {
                $table->increments('id');
                $table->char('name', 10);
                $table->integer('privilege')->default(0);
                $table->char('password', 255)->default('000000');
                $table->char('sex', 1);
                $table->char('phone', 20)->nullable();
                $table->char('mail', 64);
                $table->char('qq', 12)->nullable();
                $table->char('wechat', 32)->nullable();
                $table->char('blog', 128)->nullable();
                $table->char('github', 128)->nullable();
                $table->char('native', 128)->nullable();
                $table->char('grade', 4);
                $table->char('major', 32);
                $table->char('workplace', 128)->nullable();
                $table->char('job', 32)->nullable();
                $table->engine = 'MyISAM';
                $table->unique('mail');
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
        Schema::drop('cs_user');
    }
}
