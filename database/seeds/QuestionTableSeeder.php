 <?php
 
 use App\Question;
 use Illuminate\Database\Seeder;

 class QuestionTableSeeder extends Seeder {

  public function run()
  {
    DB::table('app_discuss_question')->delete();

    for ($i=0; $i < 10; $i++) {
      Question::create([
        'title'   => 'Title '.$i,
        'content'    =>  'content'.$i,
        'author_id' => 1,
        'tags'=>'c'
      ]);
    }
  }
}