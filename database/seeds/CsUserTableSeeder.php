<?php

use Illuminate\Database\Seeder;
use App\Models\CsUser;

class CsUserTableSeeder extends Seeder
{
    public function run() 
    {
        DB::table('cs_user')
            ->delete();
        for ($i = 0; $i < 100; $i++) {
                CsUser::create(
                    [
                    'name' => '测试'.$i,
                    'password' => md5(123456),
                    'sex' => '男',
                    'mail' => '123456'.$i.'@qq.com',
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
?>
