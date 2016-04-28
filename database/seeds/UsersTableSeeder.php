<?php

/**
 * File: database/seeds/UsersTableSeeder.php
 *
 * UsersTableSeeder Seeder
 *
 * PHP Version 5.5.9
 *
 * @category Database\Seeds
 * @package  UsersTableSeeder
 * @author   Jensyn <zhangyongjun369@gmail.com>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://api.xiyoulinux.org
 */

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

/**
 * Class: UsersTableSeeder
 *
 * @category Database\Seeds
 * @package  UsersTableSeeder
 * @author   Jensyn <zhangyongjun369@gmail.com>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://api.xiyoulinux.org
 */
class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->delete();

        for ($i = 0; $i < 5; $i++) {
            User::create(
                [
                    'name' => '测试'.$i,
                    'password' => Hash::make(md5('secret')),
                    'sex' => '男',
                    'email' => '123456'.$i.'@qq.com',
                    'qq' => '12345678'.$i,
                    'wechat' => 'qwert'.$i,
                    'blog' => 'iiii'.$i.'@Gmail.com',
                    'github' => 'heheheh'.$i.'@github.com',
                    'native' => '汉',
                    'grade' => '2005'+$i,
                    'major' => '计算机学院',
                    'workplace' => '中国',
                    'job' => '软件开发工程师',
                ]
            );
        }
    }
}
