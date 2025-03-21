<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('記事登録') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('articles.store') }}">
                        @csrf

                        <!-- 取材対象会社 -->
                        <div class="mb-4">
                            <x-input-label for="company_id" :value="__('取材対象会社')" />
                            <select id="company_id" name="company_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">-- 会社を選択 --</option>
                                @foreach ($companies as $id => $name)
                                    <option value="{{ $id }}" {{ old('company_id', request('company_id')) == $id ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('company_id')" class="mt-2" />
                        </div>

                        <!-- タイトル -->
                        <div class="mb-4">
                            <x-input-label for="title" :value="__('タイトル')" />
                            <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title')" required autofocus />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <!-- 内容 -->
                        <div class="mb-4">
                            <x-input-label for="content" :value="__('内容')" />
                            <textarea id="content" name="content" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="10" required>{{ old('content') }}</textarea>
                            <x-input-error :messages="$errors->get('content')" class="mt-2" />
                        </div>

                        <!-- 取材日 -->
                        <div class="mb-4">
                            <x-input-label for="interview_date" :value="__('取材日')" />
                            <x-text-input id="interview_date" class="block mt-1 w-full" type="date" name="interview_date" :value="old('interview_date')" />
                            <x-input-error :messages="$errors->get('interview_date')" class="mt-2" />
                        </div>

                        <!-- 公開状態 -->
                        <div class="mb-4">
                            <div class="flex items-center">
                                <input id="is_published" type="checkbox" name="is_published" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" {{ old('is_published') ? 'checked' : '' }}>
                                <label for="is_published" class="ml-2 text-sm text-gray-600">
                                    {{ __('公開する') }}
                                </label>
                            </div>
                            <x-input-error :messages="$errors->get('is_published')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ url()->previous() }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 mr-2">
                                キャンセル
                            </a>
                            <x-primary-button>
                                {{ __('登録') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
