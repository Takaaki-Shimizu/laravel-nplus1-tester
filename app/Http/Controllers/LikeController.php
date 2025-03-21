<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function toggle(Article $article)
    {
        $user = Auth::user();
        
        $like = Like::where('article_id', $article->id)
                    ->where('user_id', $user->id)
                    ->first();
        
        if ($like) {
            $like->delete();
            $action = 'unliked';
        } else {
            Like::create([
                'article_id' => $article->id,
                'user_id' => $user->id,
            ]);
            $action = 'liked';
        }
        
        return back()->with('success', "記事を{$action}しました。");
    }
}
