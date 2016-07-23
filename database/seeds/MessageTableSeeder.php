<?php

use Illuminate\Database\Seeder;
use App\Models\Message;

class MessageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('messages')->delete();

        for ($i = 0; $i < 10; $i++) {
            Message::create(
                [
                'type' => '1',
                'author_id' => 6,
                'app_id' => 0,
                'receiver_id' => $i%7,
                'title' => 'title' . $i,
                'content' => 'content' . $i,
                'status' => $i%3,
                ]
            );
        }
    }
}
