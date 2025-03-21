<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Like;
use App\Models\User;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class LikeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        
        // ユーザーIDを取得
        $userIds = User::pluck('id')->toArray();
        
        // ユーザーが存在しない場合はダミーユーザーを追加
        if (count($userIds) < 5) {
            for ($i = 1; $i <= (5 - count($userIds)); $i++) {
                $userId = User::create([
                    'name' => "ダミーユーザー{$i}",
                    'email' => "dummy{$i}@example.com",
                    'password' => bcrypt('password'),
                ])->id;
                $userIds[] = $userId;
            }
        }
        
        // 全ての記事に対していいねを2件ずつ追加
        $articles = Article::all();
        
        foreach ($articles as $article) {
            // 各記事に対して、重複しないようにランダムなユーザーを2人選択
            $randomUsers = $faker->randomElements($userIds, 2);
            
            foreach ($randomUsers as $userId) {
                // 既に同じユーザーが同じ記事にいいねしていないか確認
                $exists = Like::where('article_id', $article->id)
                             ->where('user_id', $userId)
                             ->exists();
                
                if (!$exists) {
                    Like::create([
                        'article_id' => $article->id,
                        'user_id' => $userId,
                        'created_at' => $faker->dateTimeBetween('-3 months', 'now'),
                        'updated_at' => $faker->dateTimeBetween('-1 month', 'now'),
                    ]);
                }
            }
        }
    }
}
