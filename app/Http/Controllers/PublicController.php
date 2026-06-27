<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Genre;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PublicController extends Controller
{
    public function home(Request $request)
    {
        $query = Article::with(['author', 'genres', 'ratings', 'comments'])
            ->where('status', 'published');

        if ($request->has('search') && $request->search) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->has('genre') && $request->genre) {
            $query->whereHas('genres', function ($q) use ($request) {
                $q->where('slug', $request->genre);
            });
        }

        $articles = $query->latest()->paginate(12)->withQueryString();
        $genres = Genre::orderBy('name')->get();

        return Inertia::render('Welcome', [
            'articles' => $articles,
            'genres' => $genres,
            'filters' => $request->only(['search', 'genre'])
        ]);
    }
}
