<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Models\VendorCategory;

class HomeController extends Controller
{
    public function index()
    {
        $categories = VendorCategory::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->withCount(['vendors' => fn ($q) => $q->where('status', 'approved')])
            ->get();

        $featuredVendors = Vendor::query()
            ->where('status', 'approved')
            ->with(['category', 'media', 'services' => fn ($q) => $q->where('status', 'published')->where('is_active', true), 'services.media'])
            ->orderByDesc('is_featured')
            ->orderByDesc('rating_count')
            ->take(3)
            ->get();

        $totalVendors = Vendor::where('status', 'approved')->count();

        return view('frontend.home', compact('categories', 'featuredVendors', 'totalVendors'));
    }
}
