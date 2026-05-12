<?php

namespace App\Http\Controllers;

use App\Models\KnowledgeArticle;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class KnowledgeArticleController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $tab  = $request->get('tab', 'published');

        $query = KnowledgeArticle::with(['category', 'author']);

        if ($user->hasAnyRole(['admin', 'agent'])) {
            if ($tab === 'draft') {
                $query->where('status', 'draft');
            } elseif ($tab === 'mine') {
                $query->where('author_id', $user->id);
            } else {
                $query->where('status', 'published');
            }
        } else {
            $query->where('status', 'published');
            $tab = 'published';
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('content', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $articles   = $query->orderByDesc('updated_at')->paginate(12)->withQueryString();
        $categories = Category::where('is_active', true)->get();

        $counts = [];
        if ($user->hasAnyRole(['admin', 'agent'])) {
            $counts['published'] = KnowledgeArticle::where('status', 'published')->count();
            $counts['draft']     = KnowledgeArticle::where('status', 'draft')->count();
            $counts['mine']      = KnowledgeArticle::where('author_id', $user->id)->count();
        }

        return view('knowledge.index', compact('articles', 'categories', 'tab', 'counts'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('knowledge.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'content'     => 'required|string',
            'category_id' => 'nullable|exists:categories,id',
            'status'      => 'required|in:draft,published',
        ]);

        $article = KnowledgeArticle::create([
            'title'       => $request->title,
            'content'     => $request->content,
            'category_id' => $request->category_id,
            'status'      => $request->status,
            'author_id'   => auth()->id(),
            'slug'        => Str::slug($request->title) . '-' . time(),
        ]);

        $msg = $request->status === 'draft'
            ? 'Artikel disimpan sebagai draft.'
            : 'Artikel berhasil dipublikasikan.';

        return $request->status === 'draft'
            ? redirect()->route('knowledge.index', ['tab' => 'draft'])->with('success', $msg)
            : redirect()->route('knowledge.show', $article)->with('success', $msg);
    }

    public function show(KnowledgeArticle $knowledge)
    {
        if ($knowledge->status === 'draft') {
            $user = auth()->user();
            if (!$user->hasAnyRole(['admin', 'agent']) && $knowledge->author_id !== $user->id) {
                abort(403, 'Artikel ini belum dipublikasikan.');
            }
        }

        $knowledge->increment('views');

        $related = KnowledgeArticle::where('category_id', $knowledge->category_id)
            ->where('id', '!=', $knowledge->id)
            ->where('status', 'published')
            ->limit(5)
            ->get();

        return view('knowledge.show', compact('knowledge', 'related'));
    }

    public function edit(KnowledgeArticle $knowledge)
    {
        $user = auth()->user();
        if (!$user->hasAnyRole(['admin']) && $knowledge->author_id !== $user->id) {
            abort(403);
        }

        $categories = Category::where('is_active', true)->get();
        return view('knowledge.edit', compact('knowledge', 'categories'));
    }

    public function update(Request $request, KnowledgeArticle $knowledge)
    {
        $user = auth()->user();
        if (!$user->hasAnyRole(['admin']) && $knowledge->author_id !== $user->id) {
            abort(403);
        }

        $request->validate([
            'title'       => 'required|string|max:255',
            'content'     => 'required|string',
            'category_id' => 'nullable|exists:categories,id',
            'status'      => 'required|in:draft,published',
        ]);

        $knowledge->update([
            'title'       => $request->title,
            'content'     => $request->content,
            'category_id' => $request->category_id,
            'status'      => $request->status,
        ]);

        $msg = $request->status === 'draft'
            ? 'Artikel disimpan sebagai draft.'
            : 'Artikel berhasil dipublikasikan.';

        return redirect()->route('knowledge.show', $knowledge)->with('success', $msg);
    }

    public function destroy(KnowledgeArticle $knowledge)
    {
        $user = auth()->user();
        if (!$user->hasAnyRole(['admin']) && $knowledge->author_id !== $user->id) {
            abort(403);
        }

        $knowledge->delete();
        return redirect()->route('knowledge.index')->with('success', 'Artikel berhasil dihapus.');
    }

    public function publish(KnowledgeArticle $knowledge)
    {
        $user = auth()->user();
        if (!$user->hasAnyRole(['admin']) && $knowledge->author_id !== $user->id) {
            abort(403);
        }

        $knowledge->update(['status' => 'published']);
        return back()->with('success', "Artikel \"{$knowledge->title}\" berhasil dipublikasikan.");
    }
}
