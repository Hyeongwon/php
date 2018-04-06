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
        // $this->call(UsersTableSeeder::class);

        /*if(config('database.default') != 'sqlite') {

            DB::statement('SET FOREIGN_KEY_CHECKS=0');
        }*/

        /*App\User::truncate();
        $this->call(UsersTableSeeder::class);

        App\Article::truncate();
        $this->call(ArticlesTableSeeder::class);*/

        $sqlite = in_array(config('database.default'), ['sqlite', 'testing'], true);
        if (! $sqlite) {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
        }

        /* 태그 */
        App\Tag::truncate();
        DB::table('article_tag')->truncate();
        $tags = config('project.tags');

        foreach($tags as $slug => $name) {

            App\Tag::create([
                'name' => $name,
                'slug' => str_slug($slug)
            ]);
        }

        $this->command->info('Seeded: tags table');

        /*변수 선언*/
        $faker = app(Faker\Generator::class);
        $user = App\User::all();
        $articles = App\Article::all();
        $tags = App\Tag::all();

        /*아티클과 태그 연결*/
        foreach ($articles as $article) {
            $article->tags()->sync(
                $faker->randomElements(
                    $tags->pluck('id')->toArray(), rand(1, 3)
                )
            );
        }

        $this->command->info('Seeded: article_tag table');

        //최상위 댓글
        $articles->each(function($article) {

            $article->comments()->save(factory(App\Comment::class)->make());
            $article->comments()->save(factory(App\Comment::class)->make());
        });

        //자식 댓글
        $articles->each(function ($article) use ($faker) {

            $commentIds = App\Comment::pluck('id')->toArray();

            foreach (range(1, 5) as $index) {

                $article->comments()->save(

                    factory(App\Comment::class)->make([
                        'parent_id' => $faker->randomElement($commentIds),
                    ])
                );
            }
        });

        $this->command->info('Seeded: comments table');

        $comments = App\Comment::all();

        $comments->each(function ($comment) {

            $comment->votes()->save(factory(App\Vote::class)->make());
            $comment->votes()->save(factory(App\Vote::class)->make());
            $comment->votes()->save(factory(App\Vote::class)->make());
        });

        $this->command->info('Seeded: votes table');

        foreach (range(1, 10) as $index) {

            $path = $faker->image(attachments_path());
            $filename = File::basename($path);
            $bytes = File::size($path);
            $mime = File::mimeType($path);
            $this->command->warn("File saved: {$filename}");

            factory(App\Attachment::class)->create([
               'filename' => $filename,
               'bytes' => $bytes,
                'mime' => $mime,
                'created_at' => $faker->dateTimeBetween('-1 month'),
            ]);
        }
    }
}
