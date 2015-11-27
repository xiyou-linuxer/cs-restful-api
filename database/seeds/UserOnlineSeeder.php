<?php

use Illuminate\Database\Seeder;
use App\Models\useronline;

class UserOnlineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('cs_online')->delete();

        for ($i = 1; $i < 100; $i++) {
            useronline::create(
                [
                    'id'=>$i,
                    'time'=>time()-($i*100),
                    ]
            );
        }
    }
}
