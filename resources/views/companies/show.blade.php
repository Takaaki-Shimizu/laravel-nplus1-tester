<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('会社詳細') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- 成功メッセージ -->
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-2">{{ $company->name }}</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600 mb-1">説明:</p>
                                <p class="mb-3">{{ $company->description ?: '説明はありません' }}</p>
                                
                                <p class="text-sm text-gray-600 mb-1">住所:</p>
                                <p class="mb-3">{{ $company->address ?: '住所は登録されていません' }}</p>
                            </div>
                            
                            <div>
                                <p class="text-sm text-gray-600 mb-1">電話番号:</p>
                                <p class="mb-3">{{ $company->phone ?: '電話番号は登録されていません' }}</p>
                                
                                <p class="text-sm text-gray-600 mb-1">メールアドレス:</p>
                                <p class="mb-3">{{ $company->email ?: 'メールアドレスは登録されていません' }}</p>
                                
                                <p class="text-sm text-gray-600 mb-1">ウェブサイト:</p>
                                <p class="mb-3">
                                    @if ($company->website)
                                        <a href="{{ $company->website }}" target="_blank" class="text-blue-600 hover:underline">{{ $company->website }}</a>
                                    @else
                                        ウェブサイトは登録されていません
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-between">
                        <div>
                            <a href="{{ route('companies.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400">
                                一覧に戻る
                            </a>
                        </div>
                        
                        <div class="flex">
                            <a href="{{ route('companies.articles', $company) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 mr-2">
                                記事一覧
                            </a>
                            <a href="{{ route('companies.edit', $company) }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-400 mr-2">
                                編集
                            </a>
                            <form action="{{ route('companies.destroy', $company) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500" onclick="return confirm('本当に削除しますか？')">
                                    削除
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 関連記事セクション -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">最近の記事</h3>
                        <a href="{{ route('articles.create') }}?company_id={{ $company->id }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500">
                            新規記事作成
                        </a>
                    </div>

                    @if ($company->articles->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white border">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">タイトル</th>
                                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">作成者</th>
                                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">取材日</th>
                                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">いいね数</th>
                                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ステータス</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($company->articles->take(5) as $article)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap border-b border-gray-200">
                                                <a href="{{ route('articles.show', $article) }}" class="text-indigo-600 hover:text-indigo-900">
                                                    {{ $article->title }}
                                                </a>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap border-b border-gray-200">{{ $article->user->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap border-b border-gray-200">{{ $article->interview_date ? date('Y-m-d', strtotime($article->interview_date)) : '未設定' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap border-b border-gray-200">{{ $article->likes->count() }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap border-b border-gray-200">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $article->is_published ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                                    {{ $article->is_published ? '公開' : '下書き' }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        @if ($company->articles->count() > 5)
                            <div class="mt-4 text-center">
                                <a href="{{ route('companies.articles', $company) }}" class="text-indigo-600 hover:text-indigo-900">
                                    すべての記事を表示 ({{ $company->articles->count() }})
                                </a>
                            </div>
                        @endif
                    @else
                        <p class="text-gray-500">この会社に関する記事はまだありません。</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
