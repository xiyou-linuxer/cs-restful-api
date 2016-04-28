<?php

use Illuminate\Database\Seeder;
use App\Models\Apps;

class AppsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('Apps')->delete();

        for ($i = 0; $i < 5; $i++) {
            Apps::create(
                [
                    'name' => 'name'.$i,
                    'description' => 'description' . $i,
                    'key'   => 'key'.$i,
                    'status' => '1',
                    'redirect_url' => 'http://www.'.$i.'.com',

                ]
            );
        }
    }
}
