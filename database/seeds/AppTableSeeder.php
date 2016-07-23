<?php

use App\Models\App;
use Illuminate\Database\Seeder;

class AppTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('apps')->delete();

        App::create(
            [
                'client_id'    => 'hawwa',
                'name'         => 'Hawwa',
                'author_id'    => 6,
                'homepage'     => 'http://121.42.144.117:2111',
                'logo'         => 'https://www.prepbootstrap.com/bootstrap-theme/shield/preview/images/team/team04.jpg',
                'description'  => '西邮Linux兴趣小组资源管理平台',
                'secret'       => '$2y$10$8Gz5X7XkQtVzwFU8C9zSQ.FzIH6OZNd5D',
                'redirect_uri' => 'http://121.42.144.117:2111/oauth/callback',
                'status'       => 3
            ]
        );
        App::create(
            [
                'client_id'    => 'wiki',
                'name'         => 'Wiki',
                'author_id'    => 6,
                'homepage'     => 'http://121.42.144.117:2333',
                'logo'         => 'https://www.prepbootstrap.com/bootstrap-theme/shield/preview/images/team/team04.jpg',
                'description'  => '西邮Linux兴趣小组Wiki系统',
                'secret'       => '$2y$10$8Gz5X7XkQtVzwFU8C9zSQ.FzIH6OZNd5D',
                'redirect_uri' => 'http://121.42.144.117:2333/oauth/callback',
                'status'       => 3
            ]
        );
    }
}
