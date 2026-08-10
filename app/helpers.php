<?php

use Illuminate\Support\Str;

if (! function_exists('rupiah')) {
    function rupiah(float|int|null $amount, bool $compact = false): string
    {
        if ($amount === null) {
            return 'Hubungi untuk harga';
        }

        $value = (float) $amount;

        if ($compact && $value >= 1_000_000) {
            $jt = $value / 1_000_000;
            $formatted = rtrim(rtrim(number_format($jt, 1, ',', '.'), '0'), ',');
            return 'Rp ' . $formatted . ' jt';
        }

        return 'Rp ' . number_format($value, 0, ',', '.');
    }
}

if (! function_exists('price_range_label')) {
    function price_range_label(?string $key): string
    {
        return [
            '1' => 'Sampai Rp 5 jt',
            '2' => 'Rp 5 jt - 15 jt',
            '3' => 'Rp 15 jt - 30 jt',
            '4' => 'Rp 30 jt - 50 jt',
            '5' => 'Diatas Rp 50 jt',
        ][$key] ?? '';
    }
}

if (! function_exists('capacity_range_label')) {
    function capacity_range_label(?string $key): string
    {
        return [
            '1' => 'hingga 50 tamu',
            '2' => '50 - 100 tamu',
            '3' => '100 - 300 tamu',
            '4' => '300 - 500 tamu',
            '5' => 'diatas 500 tamu',
        ][$key] ?? '';
    }
}

if (! function_exists('active_filter_query')) {
    function active_filter_query(array $overrides = [], array $except = []): string
    {
        $query = request()->query();

        foreach ($except as $key) {
            unset($query[$key]);
        }

        foreach ($overrides as $key => $value) {
            if (is_null($value) || $value === '' || $value === false) {
                unset($query[$key]);
                continue;
            }
            $query[$key] = $value;
        }

        // Always drop page when changing filters
        unset($query['page']);

        return http_build_query($query);
    }
}