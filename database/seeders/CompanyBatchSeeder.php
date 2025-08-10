<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class CompanyBatchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('ja_JP');
        
        // 10,000件の会社データを効率的に生成（バッチ挿入）
        $batchSize = 500;
        $totalRecords = 10000;
        
        for ($batch = 0; $batch < $totalRecords / $batchSize; $batch++) {
            $companies = [];
            
            for ($i = 0; $i < $batchSize; $i++) {
                $companies[] = [
                    'name' => $faker->company,
                    'description' => $faker->paragraph(3),
                    'address' => $faker->address,
                    'phone' => $faker->phoneNumber,
                    'email' => $faker->companyEmail,
                    'website' => $faker->url,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            
            Company::insert($companies);
            echo "Company batch " . ($batch + 1) . " completed (" . (($batch + 1) * $batchSize) . "/" . $totalRecords . ")\n";
        }
        
        // ガベージコレクション実行でメモリクリア
        gc_collect_cycles();
    }
}