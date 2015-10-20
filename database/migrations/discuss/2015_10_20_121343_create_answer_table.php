<?php

/**
 * Migration file for table of app_discuss_answer
 *
 * PHP version 5.5.9
 *
 * @category Database
 * @package  CS
 * @author   dreamleilei <1679211339@qq.com>
 * @license  http://opensource.org/licenses/MIT MIT
 * @version  GIT: 
 * @link     https://github.com/xiyou-linuxer/cs-restful-api
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Migration class for table of app_discuss_answer
 *
 * PHP version 5.5.9
 *
 * @category Database
 * @package  CS
 * @author   dreamleilei <1679211339@qq.com>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     https://github.com/xiyou-linuxer/cs-restful-api
 */

class CreateAnswerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'app_discuss_answer',
            function (Blueprint $table) {
                $table->increments('id');
                $table->integer('question_id');
                $table->integer('author_id');
                $table->text('content');
                $table->string('vote', 4096)->nullable();
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
        Schema::drop('app_discuss_answer');
    }
}