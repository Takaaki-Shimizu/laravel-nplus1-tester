<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ArticleBatchSeeder extends Seeder
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
        
        // 直近に作成された10,000社のIDを取得
        $companyIds = Company::orderBy('id', 'desc')->limit(10000)->pluck('id')->toArray();
        
        if (empty($companyIds)) {
            echo "会社データが存在しません。先にCompanyBatchSeederを実行してください。\n";
            return;
        }
        
        // 各社に3記事ずつ作成
        $batchSize = 1000;
        $articlesPerCompany = 3;
        
        for ($i = 0; $i < count($companyIds); $i += $batchSize) {
            $articles = [];
            $batchCompanyIds = array_slice($companyIds, $i, $batchSize);
            
            foreach ($batchCompanyIds as $companyId) {
                for ($j = 1; $j <= $articlesPerCompany; $j++) {
                    $articles[] = [
                        'company_id' => $companyId,
                        'user_id' => $faker->randomElement($userIds),
                        'title' => $faker->realText(30),
                        'content' => $faker->realText(500),
                        'interview_date' => $faker->dateTimeBetween('-1 year', 'now'),
                        'is_published' => $faker->boolean(80),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
            
            Article::insert($articles);
            echo "Article batch completed for " . count($batchCompanyIds) . " companies (" . count($articles) . " articles)\n";
            
            // メモリクリア
            unset($articles);
        }
        
        // ガベージコレクション実行でメモリクリア
        gc_collect_cycles();
    }
}