<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Company;
use App\Models\User;
use App\Models\Like;
use Illuminate\Database\Seeder;

class MiniArticleSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();
        
        // 記事データが存在しない会社に記事を作成（メモリ効率化）
        $companiesWithoutArticles = Company::whereDoesntHave('articles')->take(10000)->get();
        
        foreach ($companiesWithoutArticles as $company) {
            for ($i = 1; $i <= 3; $i++) {
                $article = Article::create([
                    'company_id' => $company->id,
                    'user_id' => $user->id,
                    'title' => '会社' . $company->id . 'の記事' . $i,
                    'content' => '会社' . $company->id . 'の記事内容' . $i,
                    'interview_date' => now(),
                    'is_published' => true
                ]);
                
                // 記事1は2いいね、記事2は1いいね、記事3は0いいね
                $likeCount = ($i == 1) ? 2 : (($i == 2) ? 1 : 0);
                for ($j = 0; $j < $likeCount; $j++) {
                    Like::firstOrCreate([
                        'article_id' => $article->id,
                        'user_id' => $user->id
                    ]);
                }
            }
            
            if ($company->id % 1000 == 0) {
                echo "Processed company {$company->id}\n";
            }
        }
    }
}