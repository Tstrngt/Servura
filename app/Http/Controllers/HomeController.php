<?php

namespace App\Http\Controllers;

use App\Models\PortfolioItem;
use App\Models\Service;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredPortfolio = PortfolioItem::active()
            ->featured()
            ->ordered()
            ->take(3)
            ->get();

        $popularServices = Service::active()
            ->popular()
            ->ordered()
            ->take(3)
            ->get();

        return view('home', compact('featuredPortfolio', 'popularServices'));
    }
}
