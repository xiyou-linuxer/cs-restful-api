<?php
/**
 * Migtation to create cs_mail table
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
 * Descrip the migration class for CsMail
 *
 * PHP version 5.6
 *
 * @category PHP
 * @package  PHP_Laveral
 * @author   teddyliao <sxliao@foxmail.com>
 * @license  http://xiyoulinux.org BSD Licence
 * @link     http://cs.xiyoulinux.org
 */

class CreateCsMailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'cs_mail',
            function (Blueprint $table) {
                $table->increments('id');
                $table->integer('fromuid');
                $table->timestamp('added_on');
                $table->char('title', 64);
                $table->text('content');
                $table->integer('isdraft');
                $table->mediumText('touid');
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
        Schema::drop('cs_user');
    }
}
