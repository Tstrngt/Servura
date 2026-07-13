<?php

namespace App\Http\Controllers;

use App\Models\PortfolioItem;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function index()
    {
        $portfolioItems = PortfolioItem::active()
            ->ordered()
            ->get();

        return view('about', compact('portfolioItems'));
    }
}
