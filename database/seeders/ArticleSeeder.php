<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('ja_JP');
        
        // ユーザーIDを取得
        $userIds = User::pluck('id')->toArray();
        if (empty($userIds)) {
            // ユーザーが存在しない場合はダミーユーザーを作成
            $userId = User::create([
                'name' => 'テストユーザー',
                'email' => 'test@example.com',
                'password' => bcrypt('password'),
            ])->id;
            $userIds = [$userId];
        }
        
        // 全ての会社に対して2件ずつ記事を作成
        $companies = Company::all();
        
        foreach ($companies as $company) {
            for ($i = 1; $i <= 2; $i++) {
                Article::create([
                    'company_id' => $company->id,
                    'user_id' => $faker->randomElement($userIds),
                    'title' => $faker->realText(30), // 30文字程度のタイトル
                    'content' => $faker->realText(500), // 500文字程度の本文
                    'interview_date' => $faker->dateTimeBetween('-1 year', 'now'),
                    'is_published' => $faker->boolean(80), // 80%の確率で公開
                    'created_at' => $faker->dateTimeBetween('-6 months', 'now'),
                    'updated_at' => $faker->dateTimeBetween('-3 months', 'now'),
                ]);
            }
        }
    }
}
