<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class CompanyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index(Request $request)
    {
        $query = Company::query();
        
        // 検索機能
        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $query->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%")
                  ->orWhere('address', 'like', "%{$searchTerm}%");
        }

        // CSVダウンロード
        if ($request->has('csv')) {
            return $this->exportCsv($query->get());
        }
        
        $companies = $query->paginate(10);
        
        return view('companies.index', compact('companies'));
    }

    public function create()
    {
        return view('companies.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
        ]);
        
        Company::create($validated);
        
        return redirect()->route('companies.index')
                         ->with('success', '会社情報が登録されました。');
    }

    public function show(Company $company)
    {
        return view('companies.show', compact('company'));
    }

    public function edit(Company $company)
    {
        return view('companies.edit', compact('company'));
    }

    public function update(Request $request, Company $company)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
        ]);
        
        $company->update($validated);
        
        return redirect()->route('companies.index')
                         ->with('success', '会社情報が更新されました。');
    }

    public function destroy(Company $company)
    {
        $company->delete();
        
        return redirect()->route('companies.index')
                         ->with('success', '会社情報が削除されました。');
    }
    
    // CSVエクスポート機能
    protected function exportCsv($companies)
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="companies.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        // 事前に企業ごとの人気記事をまとめて取得（Eager Loading）
        // $companies = $companies->load(['articles' => function ($query) {
        //     $query->withCount('likes')
        //         ->orderBy('likes_count', 'desc')
        //         ->take(3);
        // }]);
        
        $callback = function() use ($companies) {
            $file = fopen('php://output', 'w');
            
            // ヘッダー行
            fputcsv($file, [
                'ID', '会社名', '説明', '住所', '電話番号', 'メールアドレス', 'ウェブサイト',
                '人気記事1', 'いいね数1', '人気記事2', 'いいね数2', '人気記事3', 'いいね数3'
            ]);
            
            // データ行
            foreach ($companies as $company) {
                // 会社に紐づく記事を取得し、いいね数でソートして上位3件を取得
                $topArticles = \App\Models\Article::where('company_id', $company->id)
                ->withCount('likes')
                ->orderBy('likes_count', 'desc')
                ->take(3)
                ->get();

                $row = [
                    $company->id,
                    $company->name,
                    $company->description,
                    $company->address,
                    $company->phone,
                    $company->email,
                    $company->website,
                ];

                // 事前ロードしたデータを利用（SQLが発行されない）
                // $topArticles = $company->articles;
                
                // 上位3記事とそのいいね数を追加
                for ($i = 0; $i < 3; $i++) {
                    if (isset($topArticles[$i])) {
                        $row[] = $topArticles[$i]->title;
                        $row[] = $topArticles[$i]->likes_count;
                    } else {
                        // 記事が3つない場合は空セルを追加
                        $row[] = '';
                        $row[] = '';
                    }
                }
                
                fputcsv($file, $row);
            }
            
            fclose($file);
        };
        
        return Response::stream($callback, 200, $headers);
    }
}
