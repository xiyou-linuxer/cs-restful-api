<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(OAuthSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(NewsSeeder::class);
        $this->call(MessagesSeeder::class);
        $this->call(AppsSeeder::class);
    }
}
