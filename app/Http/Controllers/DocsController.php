<?php

namespace App\Http\Controllers;

use App\Support\Docs;
use Illuminate\Http\Request;

class DocsController extends Controller
{
    public function index()
    {
        return view('docs.index', [
            'categories' => Docs::categories(),
        ]);
    }

    public function search(Request $request)
    {
        $query = trim((string) $request->query('q', ''));

        if ($query === '') {
            return redirect(Docs::url() ?: '/');
        }

        return view('docs.search', [
            'query' => $query,
            'results' => Docs::search($query),
        ]);
    }

    public function category(string $category)
    {
        $categoryMeta = collect(Docs::categories())->firstWhere('slug', $category);
        abort_unless($categoryMeta, 404);

        return view('docs.category', compact('categoryMeta'));
    }

    public function show(string $category, string $page)
    {
        $doc = Docs::page($category, $page);
        abort_unless($doc, 404);

        $categories = Docs::categories();
        $categoryMeta = collect($categories)->firstWhere('slug', $category);
        abort_unless($categoryMeta, 404);

        return view('docs.show', compact('doc', 'categories', 'categoryMeta', 'category', 'page'));
    }
}
