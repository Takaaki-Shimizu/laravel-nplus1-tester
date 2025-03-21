<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('記事詳細') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if (session('status'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="flex justify-between items-center mb-6">
                        <h1 class="text-2xl font-bold">{{ $article->title }}</h1>
                        <a href="{{ route('articles.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 active:bg-gray-500 focus:outline-none focus:border-gray-500 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                            記事一覧へ戻る
                        </a>
                    </div>
                    
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                <a href="{{ route('companies.show', $article->company->id) }}" class="hover:underline">
                                    {{ $article->company->name }}
                                </a>
                            </span>
                            <span class="text-gray-500 text-sm ml-4">
                                投稿者: {{ $article->user->name }} | 
                                {{ $article->created_at->format('Y年m月d日 H:i') }}
                                @if($article->created_at != $article->updated_at)
                                    (更新: {{ $article->updated_at->format('Y年m月d日 H:i') }})
                                @endif
                            </span>
                        </div>
                        
                        <!-- いいねボタン -->
                        <div>
                            <form action="{{ $hasLiked ? route('articles.unlike', $article->id) : route('articles.like', $article->id) }}" method="POST" class="inline">
                                @csrf
                                @if($hasLiked)
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-800 focus:outline-none focus:border-red-800 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">
                                        いいね解除 
                                        <span class="ml-2 bg-white text-red-600 text-xs font-semibold rounded-full px-2 py-1">{{ $article->likes->count() }}</span>
                                    </button>
                                @else
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-white border border-red-600 rounded-md font-semibold text-xs text-red-600 uppercase tracking-widest hover:bg-red-50 active:bg-red-100 focus:outline-none focus:border-red-800 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">
                                        いいね 
                                        <span class="ml-2 bg-red-600 text-white text-xs font-semibold rounded-full px-2 py-1">{{ $article->likes->count() }}</span>
                                    </button>
                                @endif
                            </form>
                        </div>
                    </div>

                    <!-- 取材日 -->
                    @if($article->interview_date)
                        <div class="mb-6">
                            <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                取材日: {{ \Carbon\Carbon::parse($article->interview_date)->format('Y年m月d日') }}
                            </span>
                        </div>
                    @endif

                    <!-- 記事内容 -->
                    <div class="prose max-w-none mb-6">
                        {!! nl2br(e($article->content)) !!}
                    </div>

                    <!-- 公開状態 -->
                    <div class="mt-4">
                        @if($article->is_published)
                            <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-green-100 text-green-800">公開中</span>
                        @else
                            <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">非公開</span>
                        @endif
                    </div>

                    <!-- アクションボタン -->
                    <div class="mt-8 flex justify-between">
                        <div>
                            @can('update', $article)
                                <a href="{{ route('articles.edit', $article->id) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:border-indigo-800 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    編集
                                </a>
                            @endcan
                        </div>
                        <div>
                            @can('delete', $article)
                                <form action="{{ route('articles.destroy', $article->id) }}" method="POST" onsubmit="return confirm('この記事を削除してもよろしいですか？');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-800 focus:outline-none focus:border-red-800 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">
                                        削除
                                    </button>
                                </form>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>

            <!-- 関連記事 -->
            <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">同じ会社の他の記事</h3>
                    
                    @php
                        $relatedArticles = $article->company->articles()
                            ->where('id', '!=', $article->id)
                            ->where('is_published', true)
                            ->latest()
                            ->take(3)
                            ->get();
                    @endphp
                    
                    @if($relatedArticles->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            @foreach($relatedArticles as $relatedArticle)
                                <div class="border rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300">
                                    <div class="p-4">
                                        <h4 class="font-medium text-lg mb-2">
                                            <a href="{{ route('articles.show', $relatedArticle->id) }}" class="text-indigo-600 hover:text-indigo-800 hover:underline">
                                                {{ Str::limit($relatedArticle->title, 40) }}
                                            </a>
                                        </h4>
                                        <p class="text-gray-500 text-sm">
                                            {{ $relatedArticle->created_at->format('Y年m月d日') }}
                                            <span class="ml-2">
                                                いいね: {{ $relatedArticle->likes->count() }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500">同じ会社の他の記事はありません。</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
