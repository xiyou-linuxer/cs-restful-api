<?php
/**
 * User table migration file.
 *
 * User table migration file.
 *
 * PHP version 5.5.9
 *
 * @category Database/Migrations
 * @package  CreateUsersTable
 * @author   Jensyn <zhangyongjun369@gmail.com>
 * @license  http://opensource.org/licenses/MIT MIT
 * @version  GIT:
 * @link     http://api.xiyoulinux.org
*/

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * CreateUsersTable
 *
 * PHP version 5.6
 *
 * @category Database/Migrations
 * @package  CreateUsersTable
 * @author   Jensyn <zhangyongjun369@gmail.com>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://api.xiyoulinux.org
 */
class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'users',
            function (Blueprint $table) {
                $table->increments('id');
                $table->char('name', 32);
                $table->char('email', 64)->unique();
                $table->char('password', 255)->default('000000');
                $table->integer('group')->default(0);
                $table->char('sex', 1);
                $table->char('phone', 20)->nullable();
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
                $table->rememberToken();
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
        Schema::drop('users');
    }
}
