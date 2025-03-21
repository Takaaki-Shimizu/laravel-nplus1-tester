@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>記事一覧</span>
                    <a href="{{ route('articles.create') }}" class="btn btn-primary btn-sm">新規記事作成</a>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <!-- 検索フォーム -->
                    <form method="GET" action="{{ route('articles.index') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <input type="text" name="search" class="form-control" placeholder="タイトル・内容で検索" value="{{ request('search') }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <select name="company_id" class="form-control">
                                    <option value="">会社を選択</option>
                                    @foreach($companies as $company)
                                        <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>
                                            {{ $company->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-outline-primary w-100">検索</button>
                            </div>
                        </div>
                    </form>

                    @if($articles->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>タイトル</th>
                                        <th>会社</th>
                                        <th>いいね数</th>
                                        <th>作成日</th>
                                        <th>アクション</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($articles as $article)
                                        <tr>
                                            <td>{{ $article->id }}</td>
                                            <td>
                                                <a href="{{ route('articles.show', $article->id) }}">
                                                    {{ Str::limit($article->title, 30) }}
                                                </a>
                                            </td>
                                            <td>
                                                <a href="{{ route('companies.show', $article->company_id) }}">
                                                    {{ $article->company->name }}
                                                </a>
                                            </td>
                                            <td>{{ $article->likes->count() }}</td>
                                            <td>{{ $article->created_at->format('Y/m/d') }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('articles.edit', $article->id) }}" class="btn btn-sm btn-outline-primary me-2">編集</a>
                                                    <form action="{{ route('articles.destroy', $article->id) }}" method="POST" onsubmit="return confirm('本当に削除しますか？');" style="display: inline-block;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger">削除</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-center mt-4">
                            {{ $articles->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="alert alert-info">
                            記事が見つかりませんでした。
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
