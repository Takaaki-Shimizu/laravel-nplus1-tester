#!/bin/bash

# バッチデータ作成実行スクリプト

echo "=== Laravel バッチデータシーディング開始 ==="
echo "実行時間: $(date)"
echo ""

# メモリ制限を設定（必要に応じて調整）
export PHP_MEMORY_LIMIT=4G

# バッチシーダーを実行（メモリ制限付きで実行）
php -d memory_limit=4G artisan db:seed --class=BatchDataSeeder

echo ""
echo "=== バッチデータシーディング完了 ==="
echo "完了時間: $(date)"

# 最終確認
echo ""
echo "=== データ件数確認 ==="
php artisan tinker --execute="echo 'Companies: ' . App\Models\Company::count() . PHP_EOL; echo 'Articles: ' . App\Models\Article::count() . PHP_EOL;"