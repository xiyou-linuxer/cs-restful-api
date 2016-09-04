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

use App\Models\User;
use Illuminate\Database\Seeder;
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
class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("users")->delete();

        User::create([
            'name' => '张永军',
            'password' => Hash::make(md5('secret')),
            'sex' => '男',
            'group' => 1,
            'email' => 'zhangyongjun369@gmail.com',
            'qq' => '12345678',
            'wechat' => 'qwert',
            'blog_url' => '',
            'github_url' => '',
            'native' => '汉',
            'grade' => '2005',
            'major' => '计算机学院',
            'workplace' => '中国',
            'job' => '软件开发工程师',
        ]);
    }
}
