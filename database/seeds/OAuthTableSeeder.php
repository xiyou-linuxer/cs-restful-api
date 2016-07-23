<?php

use Illuminate\Database\Seeder;

class OAuthTableSeeder extends Seeder {

    public function run()
    {
        $config = app()->make('config');

        app('db')->table("oauth_clients")->delete();

        app('db')->table("oauth_clients")->insert([
            'id' => 'hawwa',
            'secret' => '$2y$10$8Gz5X7XkQtVzwFU8C9zSQ.FzIH6OZNd5D',
            'name' => 'Hawwa'
        ]);

        app('db')->table("oauth_clients")->insert([
            'id' => 'wiki',
            'secret' => '$2y$10$8Gz5X7XkQtVzwFU8C9zSQ.FzIH6OZNd5D',
            'name' => 'Wiki'
        ]);

        app('db')->table("oauth_client_endpoints")->delete();

        app('db')->table("oauth_client_endpoints")->insert([
            'client_id' => 'hawwa',
            'redirect_uri' => 'http://121.42.144.117:2111/oauth/callback'
        ]);

        app('db')->table("oauth_client_endpoints")->insert([
            'client_id' => 'wiki',
            'redirect_uri' => 'http://121.42.144.117:2333/oauth/callback'
        ]);
    }

}
