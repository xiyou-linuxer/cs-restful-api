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
        $this->call(OAuthTableSeeder::class);
        $this->call(UserTableSeeder::class);
        $this->call(NewsTableSeeder::class);
        $this->call(MessageTableSeeder::class);
        $this->call(AppTableSeeder::class);
    }
}
