<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $articles = $user->articles()
            ->orderByDesc('created_at')
            ->paginate(10);

        $stats = [
            'total' => $user->articles()->count(),
            'published' => $user->articles()->where('status', 'published')->count(),
            'drafts' => $user->articles()->where('status', 'draft')->count(),
        ];

        return view('front.users.articles', compact('articles', 'stats'));
    }

    public function create()
    {
        return view('front.users.article-form', ['article' => null]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:300',
            'body' => 'required|string',
            'featured_image' => 'nullable|image|max:2048',
            'status' => 'required|in:draft,published',
        ]);

        $article = new Article();
        $article->user_id = $request->user()->id;
        $article->title = $validated['title'];
        $article->slug = Article::generateSlug($validated['title']);
        $article->body = $validated['body'];
        $article->status = $validated['status'];

        if ($validated['status'] === 'published') {
            $article->published_at = now();
        }

        if ($request->hasFile('featured_image')) {
            $article->featured_image = $this->uploadFile($request->file('featured_image'), 'articles');
        }

        $article->save();

        return redirect()->route('front.users.articles')
            ->with('success', 'Article created successfully.');
    }

    public function edit(Article $article)
    {
        $this->authorizeArticle($article);

        return view('front.users.article-form', compact('article'));
    }

    public function update(Request $request, Article $article)
    {
        $this->authorizeArticle($article);

        $validated = $request->validate([
            'title' => 'required|string|max:300',
            'body' => 'required|string',
            'featured_image' => 'nullable|image|max:2048',
            'status' => 'required|in:draft,published',
        ]);

        $article->title = $validated['title'];
        $article->body = $validated['body'];
        $article->status = $validated['status'];

        if ($validated['status'] === 'published' && !$article->published_at) {
            $article->published_at = now();
        }

        if ($request->hasFile('featured_image')) {
            $this->deleteFile($article->featured_image);
            $article->featured_image = $this->uploadFile($request->file('featured_image'), 'articles');
        }

        $article->save();

        return redirect()->route('front.users.articles')
            ->with('success', 'Article updated successfully.');
    }

    public function destroy(Article $article)
    {
        $this->authorizeArticle($article);

        $this->deleteFile($article->featured_image);

        $article->delete();

        return redirect()->route('front.users.articles')
            ->with('success', 'Article deleted successfully.');
    }

    private function authorizeArticle(Article $article): void
    {
        abort_unless($article->user_id === auth()->id(), 403);
    }

    private function uploadFile($file, string $subfolder): string
    {
        $email = auth()->user()->email;
        $destination = public_path('uploads/' . $email . '/' . $subfolder);
        if (! File::isDirectory($destination)) {
            File::makeDirectory($destination, 0777, true);
        }

        $filename = time() . '-' . Str::random(12) . '.' . $file->getClientOriginalExtension();
        $file->move($destination, $filename);

        return 'uploads/' . $email . '/' . $subfolder . '/' . $filename;
    }

    private function deleteFile(?string $path): void
    {
        if (! $path) {
            return;
        }

        $fullPath = public_path($path);
        if (File::exists($fullPath)) {
            File::delete($fullPath);
        }
    }
}
