<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Vendor;
use App\Models\VendorCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PackageController extends Controller
{
    public function index(Request $request)
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

        $query = Service::query()
            ->with([
                'vendor',
                'vendor.category',
                'vendor.media',
                'category',
                'media',
            ])
            ->where('status', 'published')
            ->where('is_active', true)
            ->whereHas('vendor', fn ($q) => $q->where('status', 'approved'));

        if ($request->filled('category')) {
            $catId = $request->integer('category');
            $query->where(function ($q) use ($catId) {
                $q->where('vendor_category_id', $catId)
                    ->orWhereHas('vendor', fn ($vq) => $vq->where('vendor_category_id', $catId));
            });
        }

        if ($request->filled('city')) {
            $city = $request->string('city');
            $query->whereHas('vendor', fn ($vq) => $vq->where('city', $city));
        }

        if ($request->filled('q')) {
            $term = '%' . $request->string('q')->trim() . '%';
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', $term)
                    ->orWhere('description', 'like', $term)
                    ->orWhereHas('vendor', fn ($vq) => $vq->where('business_name', 'like', $term)->orWhere('city', 'like', $term));
            });
        }

        if ($request->filled('capacity')) {
            $capKey = (string) $request->string('capacity');
            $capRanges = [
                '1' => [0, 100],
                '2' => [100, 300],
                '3' => [300, 500],
                '4' => [500, 1000],
                '5' => [1000, null],
            ];
            if (isset($capRanges[$capKey])) {
                [$cMin, $cMax] = $capRanges[$capKey];
                if ($cMin !== null) $query->where('capacity', '>=', $cMin);
                if ($cMax !== null) $query->where('capacity', '<=', $cMax);
            }
        }

        $priceRanges = [
            '1' => [0, 5000000],
            '2' => [5000000, 15000000],
            '3' => [15000000, 30000000],
            '4' => [30000000, 50000000],
            '5' => [50000000, null],
        ];
        if ($request->filled('price') && isset($priceRanges[(string) $request->string('price')])) {
            [$min, $max] = $priceRanges[(string) $request->string('price')];
            $effectivePriceSql = 'COALESCE(discount_price, price)';
            if ($min !== null) {
                $query->whereRaw("$effectivePriceSql >= ?", [$min]);
            }
            if ($max !== null) {
                $query->whereRaw("$effectivePriceSql <= ?", [$max]);
            }
        }

        $sort = $request->string('sort', 'popular')->toString();
        match ($sort) {
            'price_asc' => $query->orderByRaw('COALESCE(discount_price, price) asc'),
            'price_desc' => $query->orderByRaw('COALESCE(discount_price, price) desc'),
            'bookings' => $query->orderByDesc('bookings_count')->orderByDesc('views_count'),
            'newest' => $query->orderByDesc('id'),
            default => $query->orderByDesc('bookings_count')->orderByDesc('is_featured')->orderByDesc('views_count'),
        };

        $packages = $query->paginate(12)->withQueryString();

        return view('frontend.packages.index', compact(
            'categories',
            'cities',
            'packages',
        ));
    }
}
