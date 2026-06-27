<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    public function store(Request $request, Article $article)
    {
        $validated = $request->validate([
            'score' => 'required|integer|min:1|max:5',
        ]);

        $article->ratings()->updateOrCreate(
            ['user_id' => Auth::id()],
            ['score' => $validated['score']]
        );

        return back();
    }
}
