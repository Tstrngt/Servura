<?php

namespace App\Http\Controllers;

use App\Support\Docs;

class DocsController extends Controller
{
    public function index()
    {
        return view('docs.index', [
            'categories' => Docs::categories(),
        ]);
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
