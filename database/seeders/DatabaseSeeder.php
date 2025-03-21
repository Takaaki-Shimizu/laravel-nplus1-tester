<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // シーダーを順番に実行
        $this->call([
            CompanySeeder::class,  // 最初に会社データを作成
            ArticleSeeder::class,  // 次に記事データを作成
            LikeSeeder::class,     // 最後にいいねデータを作成
        ]);
    }
}
