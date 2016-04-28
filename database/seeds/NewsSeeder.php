<?php

use Illuminate\Database\Seeder;
use App\Models\News;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('news')->delete();

        for ($i = 0; $i < 5; $i++) {
            News::create(
                [
                    'author_id' => $i,
                    'app_id'    => 5 - $i,
                    'content'   => 'test'.$i,
                    'topic'     => 'topic' . $i,
                ]
            );
        }
    }
}
