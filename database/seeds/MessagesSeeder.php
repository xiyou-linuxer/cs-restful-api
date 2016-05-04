<?php

use Illuminate\Database\Seeder;
use App\Models\Messages;

class MessagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('messages')->delete();

        for ($i = 0; $i < 5; $i++) {
            Messages::create(
                [
                'type' => '1',
                'author_id' => $i,
                'app_id' => 5 - $i,
                'receivers' => 'heheh',
                'title' => 'title' . $i,
                'content' => 'content' . $i,
                'status' => $i,
                ]
            );
        }
    }
}
