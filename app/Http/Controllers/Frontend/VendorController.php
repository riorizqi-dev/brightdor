<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\Service;
use App\Models\Testimonial;
use App\Models\Vendor;
use App\Models\VendorCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VendorController extends Controller
{
    public function index(Request $request, ?string $categorySlug = null)
    {
        $categories = VendorCategory::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->withCount(['vendors' => fn ($q) => $q->where('status', 'approved')])
            ->get();

        $cities = Vendor::query()
            ->where('status', 'approved')
            ->whereNotNull('city')
            ->select('city', DB::raw('count(*) as total'))
            ->groupBy('city')
            ->orderByDesc('total')
            ->pluck('total', 'city');

        $category = null;
        if ($categorySlug) {
            $category = VendorCategory::where('slug', $categorySlug)->firstOrFail();
        }

        $query = Vendor::query()
            ->with(['category', 'services' => fn ($q) => $q->where('status', 'published')->where('is_active', true)])
            ->where('status', 'approved');

        if ($category) {
            $query->where('vendor_category_id', $category->id);
        } elseif ($request->filled('category')) {
            $query->where('vendor_category_id', $request->integer('category'));
        }

        if ($request->filled('city')) {
            $query->where('city', $request->string('city'));
        }

        if ($request->filled('q')) {
            $term = '%' . $request->string('q')->trim() . '%';
            $query->where(function ($q) use ($term) {
                $q->where('business_name', 'like', $term)
                    ->orWhere('city', 'like', $term)
                    ->orWhere('description', 'like', $term)
                    ->orWhereHas('category', fn ($c) => $c->where('name', 'like', $term));
            });
        }

        if ($request->filled('rating')) {
            $query->where('rating_avg', '>=', $request->float('rating'));
        }

        $this->applyPriceRange($query, $request);
        $this->applyCapacityRange($query, $request);

        $sort = $request->string('sort', 'popular')->toString();
        $priceSortSql = '(SELECT MIN(COALESCE(discount_price, price)) FROM services WHERE services.vendor_id = vendors.id AND services.status = "published" AND services.is_active = 1)';

        match ($sort) {
            'price_asc' => $query->orderByRaw($priceSortSql . ' asc nulls last'),
            'price_desc' => $query->orderByRaw($priceSortSql . ' desc nulls last'),
            'rating' => $query->orderByDesc('rating_avg')->orderByDesc('rating_count'),
            'featured' => $query->orderByDesc('is_featured')->orderByDesc('rating_count'),
            default => $query->orderByDesc('is_featured')->orderByDesc('rating_count')->orderByDesc('rating_avg'),
        };

        $vendors = $query->paginate(12)->withQueryString();

        return view('frontend.vendors.index', compact(
            'categories',
            'cities',
            'category',
            'vendors',
        ));
    }

    public function show(Request $request, string $slug)
    {
        $vendor = Vendor::query()
            ->where('slug', $slug)
            ->where('status', 'approved')
            ->with([
                'category',
                'services' => fn ($q) => $q->where('status', 'published')->where('is_active', true)->orderBy('price'),
            ])
            ->firstOrFail();

        $gallery = Gallery::query()
            ->where('vendor_id', $vendor->id)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $portfolio = $vendor->getMedia('portfolio');

        $cover = $portfolio->first() ?? $gallery->first();

        $reviews = Testimonial::query()
            ->where('is_active', true)
            ->orderByDesc('rating')
            ->take(6)
            ->get();

        $similarVendors = Vendor::query()
            ->where('status', 'approved')
            ->where('vendor_category_id', $vendor->vendor_category_id)
            ->where('id', '!=', $vendor->id)
            ->with(['category', 'services' => fn ($q) => $q->where('status', 'published')->where('is_active', true)])
            ->take(3)
            ->get();

        return view('frontend.vendors.show', compact('vendor', 'gallery', 'portfolio', 'cover', 'reviews', 'similarVendors'));
    }

    private function applyPriceRange($query, Request $request): void
    {
        if (! $request->filled('price')) {
            return;
        }

        $ranges = [
            '1' => [0, 5_000_000],
            '2' => [5_000_000, 15_000_000],
            '3' => [15_000_000, 30_000_000],
            '4' => [30_000_000, 50_000_000],
            '5' => [50_000_000, null],
        ];

        $range = $ranges[$request->string('price')->toString()] ?? null;
        if (! $range) {
            return;
        }

        $query->whereHas('services', function ($q) use ($range) {
            $q->where('status', 'published')
                ->where('is_active', true)
                ->whereRaw('COALESCE(discount_price, price) >= ?', [$range[0]]);

            if ($range[1] !== null) {
                $q->whereRaw('COALESCE(discount_price, price) < ?', [$range[1]]);
            }
        });
    }

    private function applyCapacityRange($query, Request $request): void
    {
        if (! $request->filled('capacity')) {
            return;
        }

        $ranges = [
            '1' => [null, 50],
            '2' => [50, 100],
            '3' => [100, 300],
            '4' => [300, 500],
            '5' => [500, null],
        ];

        $range = $ranges[$request->string('capacity')->toString()] ?? null;
        if (! $range) {
            return;
        }

        $query->whereHas('services', function ($q) use ($range) {
            $q->where('status', 'published')->where('is_active', true);
            if ($range[0] !== null) {
                $q->where('capacity', '>=', $range[0]);
            }
            if ($range[1] !== null) {
                $q->where('capacity', '<', $range[1]);
            }
        });
    }
}
