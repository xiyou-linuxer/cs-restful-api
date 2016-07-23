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
                $table->string('name', 32);
                $table->string('email', 64)->unique();
                $table->string('password', 255)->default('000000');
                $table->integer('group')->default(0);
                $table->char('sex', 1);
                $table->string('phone', 20)->nullable();
                $table->string('qq', 12)->nullable();
                $table->string('wechat', 32)->nullable();
                $table->string('blog', 128)->nullable();
                $table->string('github', 128)->nullable();
                $table->string('native', 128)->nullable();
                $table->string('grade', 4);
                $table->string('major', 32);
                $table->string('workplace', 128)->nullable();
                $table->string('job', 32)->nullable();
                $table->timestamp('online_at')->nullable();
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
