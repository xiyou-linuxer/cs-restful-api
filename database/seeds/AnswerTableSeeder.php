<?php

use Illuminate\Database\Seeder;
use App\Answer;

class AnswerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::table('app_discuss_answer')->delete();
        for($i = 0; $i < 10; $i++)
        {
        	Answer::create([
        		'author_id' => 1000,
        		'question_id' => 1,
        		 'content' => 'content'.$i,
        		]);
        	
        }
    }
}
