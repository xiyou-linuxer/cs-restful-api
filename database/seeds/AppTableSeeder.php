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
        DB::table("apps")->delete();

        App::create([
            'client_id'    => 'koala',
            'name'         => 'Koala',
            'author_id'    => 1,
            'homepage_url'     => 'http://121.42.144.117:2111',
            'logo_url'         => 'https://www.prepbootstrap.com/bootstrap-theme/shield/preview/images/team/team04.jpg',
            'description'  => '西邮Linux兴趣小组资源管理平台',
            'secret'       => '$2y$10$8Gz5X7XkQtVzwFU8C9zSQ.FzIH6OZNd5D',
            'redirect_uri' => 'http://121.42.144.117:2111/connect/adam/callback',
            'status'       => 3,
            'submit_status'       => 3,
            'scopes'       => 'all'
        ]);
    }
}
