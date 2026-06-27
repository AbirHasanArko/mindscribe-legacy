<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Auth::user()->articles()->latest()->get();
        return Inertia::render('Articles/Index', [
            'articles' => $articles
        ]);
    }

    public function create()
    {
        return Inertia::render('Articles/Editor', [
            'article' => new Article(['title' => '', 'content_json' => '', 'status' => 'draft']),
            'genres' => \App\Models\Genre::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        \Illuminate\Support\Facades\Log::info('Store Payload:', $request->all());
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content_json' => 'nullable|string',
            'status' => 'required|in:draft,published',
            'genres' => 'nullable|array',
            'genres.*' => 'exists:genres,id',
            'banner' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('banner')) {
            $path = $request->file('banner')->store('banners', 'public');
            $validated['banner_url'] = '/storage/' . $path;
        }
        unset($validated['banner']);

        $article = Auth::user()->articles()->create($validated);
        if (isset($validated['genres'])) {
            $article->genres()->sync($validated['genres']);
        }

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Saved', 'article' => $article]);
        }
        return redirect()->route('articles.edit', $article->id);
    }

    public function show(Article $article)
    {
        // Public reader view
        if ($article->status !== 'published' && $article->user_id !== Auth::id()) {
            abort(403);
        }
        $article->load('author', 'genres', 'comments.user', 'ratings');
        
        return Inertia::render('Articles/Show', [
            'article' => $article
        ]);
    }

    public function edit(Article $article)
    {
        if ($article->user_id !== Auth::id()) abort(403);
        $article->load('genres');

        return Inertia::render('Articles/Editor', [
            'article' => $article,
            'genres' => \App\Models\Genre::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Article $article)
    {
        \Illuminate\Support\Facades\Log::info('Update Payload:', $request->all());
        
        if ($article->user_id !== Auth::id()) abort(403);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content_json' => 'nullable|string',
            'status' => 'required|in:draft,published',
            'genres' => 'nullable|array',
            'genres.*' => 'exists:genres,id',
            'banner' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('banner')) {
            $path = $request->file('banner')->store('banners', 'public');
            $validated['banner_url'] = '/storage/' . $path;
        }
        unset($validated['banner']);

        $article->update($validated);
        if (isset($validated['genres'])) {
            $article->genres()->sync($validated['genres']);
        }

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Saved', 'article' => $article]);
        }
        return redirect()->route('articles.edit', $article->id);
    }

    public function destroy(Article $article)
    {
        if ($article->user_id !== Auth::id()) abort(403);
        
        $article->delete();
        return redirect()->route('articles.index');
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:5120', // 5MB max
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('article_images', 'public');
            return response()->json(['url' => '/storage/' . $path]);
        }

        return response()->json(['error' => 'Image upload failed'], 400);
    }
}
