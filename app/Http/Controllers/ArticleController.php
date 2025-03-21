<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $articles = Article::with(['company', 'user'])
                           ->latest()
                           ->paginate(10);
                           
        return view('articles.index', compact('articles'));
    }

    public function create()
    {
        $companies = Company::pluck('name', 'id');
        return view('articles.create', compact('companies'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'interview_date' => 'nullable|date',
            'is_published' => 'boolean',
        ]);
        
        $validated['user_id'] = Auth::id();
        
        Article::create($validated);
        
        return redirect()->route('articles.index')
                         ->with('success', '記事が登録されました。');
    }

    public function show(Article $article)
    {
        $article->load(['company', 'user', 'likes']);
        $hasLiked = $article->likes()->where('user_id', Auth::id())->exists();
        
        return view('articles.show', compact('article', 'hasLiked'));
    }

    public function edit(Article $article)
    {
        $companies = Company::pluck('name', 'id');
        return view('articles.edit', compact('article', 'companies'));
    }

    public function update(Request $request, Article $article)
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'interview_date' => 'nullable|date',
            'is_published' => 'boolean',
        ]);
        
        $article->update($validated);
        
        return redirect()->route('articles.index')
                         ->with('success', '記事が更新されました。');
    }

    public function destroy(Article $article)
    {
        $article->delete();
        
        return redirect()->route('articles.index')
                         ->with('success', '記事が削除されました。');
    }
    
    public function companyArticles(Company $company)
    {
        $articles = Article::where('company_id', $company->id)
                           ->with(['user', 'likes'])
                           ->latest()
                           ->paginate(10);
                           
        return view('articles.company_articles', compact('company', 'articles'));
    }
}
