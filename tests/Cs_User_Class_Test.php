<?php
/**
  * TestCase class definition file
  *
  * The TestCase class is a common super class for testing our application.
  *
  * PHP version 5.6.0
  *
  * @category Test
  * @package  CS
  * @author   Jensyn <sxliao@foxmail.com>
  * @license  http://opensource.org/licenses/MIT MIT
  * @version  GIT:
  * @link     https://github.com/xiyou-linuxer/cs-xiyoulinux
  */

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
  * TestCase class definition file
  *
  * The TestCase class is a common super class for testing our application.
  *
  * PHP version 5.6.0
  *
  * @category Test
  * @package  CS
  * @author   Jensyn <sxliao@foxmail.com>
  * @license  http://opensource.org/licenses/MIT MIT
  * @link     https://github.com/xiyou-linuxer/cs-xiyoulinux
  */
class Cs_User_Class_Test extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->visit('/users')
            ->seeJson(['id'=>100,]);
            
        $this->visit('/users/2')
            ->seeJson(['id'=>2,]);
    }
}
