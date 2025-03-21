<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// ホームページのルート
Route::get('/', function () {
    return view('welcome');
});

// ダッシュボードのルート
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// 認証関連のルート（Breezeによって自動生成）
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// 会社関連のルート
Route::resource('companies', CompanyController::class);

// 記事関連のルート
Route::resource('articles', ArticleController::class);
Route::get('companies/{company}/articles', [ArticleController::class, 'companyArticles'])->name('companies.articles');

// いいね関連のルート
Route::post('articles/{article}/like', [LikeController::class, 'toggle'])->name('articles.like');

require __DIR__.'/auth.php';
