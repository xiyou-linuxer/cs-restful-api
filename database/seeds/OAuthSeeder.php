<?php

use Illuminate\Database\Seeder;

class OAuthSeeder extends Seeder {

    public function run()
    {
        $config = app()->make('config');

        app('db')->table("oauth_clients")->delete();

        app('db')->table("oauth_clients")->insert([
            'id' => 'HAWWA',
            'secret' => 'helloworld',
            'name' => 'HAWWA'
        ]);


        app('db')->table("oauth_client_endpoints")->delete();

        app('db')->table("oauth_client_endpoints")->insert([
            'client_id' => 'HAWWA',
            'redirect_uri' => 'http://121.42.144.117:2333/oauth/callback'
        ]);
    }

}
