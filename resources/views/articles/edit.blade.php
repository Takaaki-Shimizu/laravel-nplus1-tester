@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">記事編集</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('articles.update', $article->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="title" class="form-label">タイトル</label>
                            <input id="title" type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title', $article->title) }}" required autofocus>
                            @error('title')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="company_id" class="form-label">取材対象会社</label>
                            <select id="company_id" class="form-control @error('company_id') is-invalid @enderror" name="company_id" required>
                                <option value="">会社を選択</option>
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}" {{ old('company_id', $article->company_id) == $company->id ? 'selected' : '' }}>
                                        {{ $company->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('company_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label">内容</label>
                            <textarea id="content" class="form-control @error('content') is-invalid @enderror" name="content" rows="10" required>{{ old('content', $article->content) }}</textarea>
                            @error('content')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">画像（変更する場合のみ）</label>
                            <input id="image" type="file" class="form-control @error('image') is-invalid @enderror" name="image">
                            @error('image')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            
                            @if($article->image_path)
                                <div class="mt-2">
                                    <p>現在の画像:</p>
                                    <img src="{{ asset('storage/' . $article->image_path) }}" alt="Article Image" class="img-thumbnail" style="max-height: 200px;">
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" name="delete_image" id="delete_image" value="1">
                                        <label class="form-check-label" for="delete_image">
                                            画像を削除する
                                        </label>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label for="published_at" class="form-label">公開日時</label>
                            <input id="published_at" type="datetime-local" class="form-control @error('published_at') is-invalid @enderror" name="published_at" value="{{ old('published_at', $article->published_at ? date('Y-m-d\TH:i', strtotime($article->published_at)) : '') }}">
                            @error('published_at')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <small class="form-text text-muted">空白の場合は下書き状態になります</small>
                        </div>

                        <div class="mb-3">
                            <label for="tags" class="form-label">タグ（カンマ区切り）</label>
                            <input id="tags" type="text" class="form-control @error('tags') is-invalid @enderror" name="tags" value="{{ old('tags', $article->tags->pluck('name')->implode(',')) }}">
                            @error('tags')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('articles.index') }}" class="btn btn-secondary">キャンセル</a>
                            <button type="submit" class="btn btn-primary">更新する</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
