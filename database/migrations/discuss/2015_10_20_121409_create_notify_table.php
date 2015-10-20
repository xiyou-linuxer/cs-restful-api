<?php

/**
 * Migration file for table of app_discuss_notify
 *
 * PHP version 5.5.9
 *
 * @category Server
 * @package  CS
 * @author   dreamleilei <1679211339@qq.com>
 * @license  http://opensource.org/licenses/MIT MIT
 * @version  GIT: 
 * @link     https://github.com/xiyou-linuxer/cs-restful-api
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Migration class for table of app_discuss_notify
 *
 * PHP version 5.5.9
 *
 * @category Server
 * @package  CS
 * @author   dreamleilei <1679211339@qq.com>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     https://github.com/xiyou-linuxer/cs-restful-api
 */

class CreateNotifyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'app_discuss_notify',
            function (Blueprint $table) { 
                $table->increments('id');
                $table->integer('user_id');
                $table->string('content', 256);
                $table->string('link', 256)->default('#');
                $table->integer('status')->default(0);
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
        Schema::drop('app_discuss_notify');
    }
}
