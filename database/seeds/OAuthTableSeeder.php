<?php

use App\Models\OAuthScope;
use App\Models\OAuthClient;
use App\Models\OAuthClientEndPoint;
use App\Models\OAuthClientScope;
use Illuminate\Database\Seeder;

class OAuthTableSeeder extends Seeder {

    public function run()
    {
        DB::table("oauth_scopes")->delete();

        OAuthScope::create([
            'id' => 'all',
            'description' => '所有权限',
            'level' => '3'
        ]);
        OAuthScope::create([
            'id' => 'all_read',
            'description' => '所有读取权限',
            'level' => '2'
        ]);
        OAuthScope::create([
            'id' => 'all_write',
            'description' => '所有写入权限',
            'level' => '2'
        ]);
        OAuthScope::create([
            'id' => 'user_info_read',
            'description' => '读取用户信息',
            'level' => '1'
        ]);
        OAuthScope::create([
            'id' => 'user_info_write',
            'description' => '写入用户信息',
            'level' => '1'
        ]);
        OAuthScope::create([
            'id' => 'app_info_read',
            'description' => '读取应用信息',
            'level' => '1'
        ]);
        OAuthScope::create([
            'id' => 'app_info_write',
            'description' => '写入应用信息',
            'level' => '1'
        ]);
        OAuthScope::create([
            'id' => 'message_info_read',
            'description' => '读取消息信息',
            'level' => '1'
        ]);
        OAuthScope::create([
            'id' => 'new_info_write',
            'description' => '写入消息信息',
            'level' => '1'
        ]);
        OAuthScope::create([
            'id' => 'news_info_read',
            'description' => '读取动态信息',
            'level' => '1'
        ]);
        OAuthScope::create([
            'id' => 'news_info_write',
            'description' => '写入动态信息',
            'level' => '1'
        ]);

        DB::table("oauth_clients")->delete();
        OAuthClient::create([
            'id' => 'koala',
            'secret' => '$2y$10$8Gz5X7XkQtVzwFU8C9zSQ.FzIH6OZNd5D',
            'name' => 'Koala'
        ]);

        DB::table("oauth_client_endpoints")->delete();
        OAuthClientEndPoint::create([
            'client_id' => 'koala',
            'redirect_uri' => 'http://121.42.144.117:2111/connect/adam/callback'
        ]);

        DB::table("oauth_client_scopes")->delete();
        OAuthClientScope::create([
          'client_id' => 'koala',
          'scope_id' => 'all'
        ]);

    }

}
