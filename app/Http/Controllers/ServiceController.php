<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::active()
            ->servicesPage()
            ->ordered()
            ->get();

        return view('services.index', compact('services'));
    }

    public function show(Service $service)
    {
        if (!$service->is_active) {
            abort(404);
        }

        $relatedServices = Service::active()
            ->where('id', '!=', $service->id)
            ->ordered()
            ->take(3)
            ->get();

        return view('services.show', compact('service', 'relatedServices'));
    }
}
