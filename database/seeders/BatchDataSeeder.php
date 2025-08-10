<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class BatchDataSeeder extends Seeder
{
    /**
     * 10回のバッチ実行で合計100,000社と300,000記事を作成
     */
    public function run(): void
    {
        echo "=== 大量データ作成開始（10バッチ実行） ===\n";
        echo "目標: 100,000社、300,000記事\n";
        echo "バッチサイズ: 10,000社 + 30,000記事 x 10回\n\n";
        
        $totalCompaniesCreated = 0;
        $totalArticlesCreated = 0;
        
        for ($batch = 1; $batch <= 10; $batch++) {
            echo "--- バッチ {$batch}/10 開始 ---\n";
            $startTime = microtime(true);
            
            // 現在のメモリ使用量を表示
            echo "現在のメモリ使用量: " . $this->formatBytes(memory_get_usage()) . "\n";
            echo "ピークメモリ使用量: " . $this->formatBytes(memory_get_peak_usage()) . "\n";
            
            // 会社データ作成前の件数
            $companiesBeforeBatch = DB::table('companies')->count();
            
            // 会社データを作成
            echo "会社データ作成中...\n";
            Artisan::call('db:seed', ['--class' => CompanyBatchSeeder::class]);
            
            // 会社データ作成後の件数
            $companiesAfterBatch = DB::table('companies')->count();
            $companiesCreatedThisBatch = $companiesAfterBatch - $companiesBeforeBatch;
            $totalCompaniesCreated += $companiesCreatedThisBatch;
            
            // 記事データ作成前の件数
            $articlesBeforeBatch = DB::table('articles')->count();
            
            // 記事データを作成
            echo "記事データ作成中...\n";
            Artisan::call('db:seed', ['--class' => ArticleBatchSeeder::class]);
            
            // 記事データ作成後の件数
            $articlesAfterBatch = DB::table('articles')->count();
            $articlesCreatedThisBatch = $articlesAfterBatch - $articlesBeforeBatch;
            $totalArticlesCreated += $articlesCreatedThisBatch;
            
            $endTime = microtime(true);
            $executionTime = round($endTime - $startTime, 2);
            
            echo "バッチ {$batch} 完了:\n";
            echo "  - 会社: {$companiesCreatedThisBatch}件作成\n";
            echo "  - 記事: {$articlesCreatedThisBatch}件作成\n";
            echo "  - 実行時間: {$executionTime}秒\n";
            echo "  - メモリ使用量: " . $this->formatBytes(memory_get_usage()) . "\n\n";
            
            // メモリクリーンアップ
            gc_collect_cycles();
            
            // 進捗表示
            echo "=== 現在の累計 ===\n";
            echo "会社: {$totalCompaniesCreated}件 / 100,000件 (" . round(($totalCompaniesCreated / 100000) * 100, 1) . "%)\n";
            echo "記事: {$totalArticlesCreated}件 / 300,000件 (" . round(($totalArticlesCreated / 300000) * 100, 1) . "%)\n\n";
        }
        
        echo "=== 全バッチ完了 ===\n";
        echo "作成された会社: {$totalCompaniesCreated}件\n";
        echo "作成された記事: {$totalArticlesCreated}件\n";
        echo "最終メモリ使用量: " . $this->formatBytes(memory_get_usage()) . "\n";
        echo "ピークメモリ使用量: " . $this->formatBytes(memory_get_peak_usage()) . "\n";
    }
    
    private function formatBytes($size, $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        for ($i = 0; $size > 1024 && $i < count($units) - 1; $i++) {
            $size /= 1024;
        }
        return round($size, $precision) . ' ' . $units[$i];
    }
}