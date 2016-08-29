<?php

use Illuminate\Database\Seeder;

class OAuthTableSeeder extends Seeder {

    public function run()
    {
        $config = app()->make('config');

        app('db')->table("oauth_scopes")->delete();

        app('db')->table("oauth_scopes")->insert([
            'id' => 'all',
            'description' => '所有权限'
        ]);
        app('db')->table("oauth_scopes")->insert([
            'id' => 'all_read',
            'description' => '所有读取权限'
        ]);
        app('db')->table("oauth_scopes")->insert([
            'id' => 'all_write',
            'description' => '所有写入权限'
        ]);
        app('db')->table("oauth_scopes")->insert([
            'id' => 'user_info_read',
            'description' => '读取用户信息'
        ]);
        app('db')->table("oauth_scopes")->insert([
            'id' => 'user_info_write',
            'description' => '写入用户信息'
        ]);
        app('db')->table("oauth_scopes")->insert([
            'id' => 'app_info_read',
            'description' => '读取应用信息'
        ]);
        app('db')->table("oauth_scopes")->insert([
            'id' => 'app_info_write',
            'description' => '写入应用信息'
        ]);
        app('db')->table("oauth_scopes")->insert([
            'id' => 'message_info_read',
            'description' => '读取消息信息'
        ]);
        app('db')->table("oauth_scopes")->insert([
            'id' => 'new_info_write',
            'description' => '写入消息信息'
        ]);
        app('db')->table("oauth_scopes")->insert([
            'id' => 'news_info_read',
            'description' => '读取动态信息'
        ]);
        app('db')->table("oauth_scopes")->insert([
            'id' => 'news_info_write',
            'description' => '写入动态信息'
        ]);

        app('db')->table("oauth_clients")->delete();

        app('db')->table("oauth_clients")->insert([
            'id' => 'hawwa',
            'secret' => '$2y$10$8Gz5X7XkQtVzwFU8C9zSQ.FzIH6OZNd5D',
            'name' => 'Hawwa'
        ]);

        app('db')->table("oauth_client_endpoints")->delete();

        app('db')->table("oauth_client_endpoints")->insert([
            'client_id' => 'hawwa',
            'redirect_uri' => 'http://121.42.144.117:2111/connect/adam/callback'
        ]);

        app('db')->table("oauth_client_scopes")->delete();
        app('db')->table("oauth_client_scopes")->insert([
          'client_id' => 'hawwa',
          'scope_id' => 'all'
        ]);

    }

}
