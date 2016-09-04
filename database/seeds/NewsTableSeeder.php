<?php

use App\Models\News;
use Illuminate\Database\Seeder;

class NewsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('news')->delete();

        News::create(
            [
                'author_id' => 1,
                'app_id'    => 'koala',
                'content'   => '欢迎访问西邮Linux兴趣小组内部交流平台Koala(考拉)。目前系统处于内测阶段，上线的功能有：应用，消息，管理。
欢迎大家积极参与测试，我们期待你的反馈。',
                'topic'     => 'Koala',
            ]
        );
    }
}
