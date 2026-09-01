<?php

namespace App\Support;

/**
 * Single source of truth for the BrightDor palette inside the Filament panels.
 *
 * Mirrors the tokens in resources/css/app.css so the admin and vendor dashboards
 * stay visually identical to the customer-facing site.
 */
final class BrandPalette
{
    public const ROSE_50 = '#fdf3f6';

    public const ROSE_100 = '#fce7ed';

    public const ROSE_400 = '#e8749b';

    public const ROSE_500 = '#d9507e';

    /** Primary brand pink. */
    public const ROSE_600 = '#c6436a';

    /** Deep maroon. */
    public const ROSE_700 = '#a62f55';

    public const ROSE_800 = '#8a2a48';

    public const ROSE_900 = '#6e223c';

    public const GOLD_300 = '#dfc98a';

    public const GOLD_400 = '#d0b565';

    /** Brand gold accent. */
    public const GOLD_500 = '#c6af59';

    public const GOLD_600 = '#a8923f';

    public const INK_500 = '#6e6e6e';

    public const INK_900 = '#222222';

    /**
     * Ordered series colours for the dashboard charts.
     *
     * @return list<string>
     */
    public static function chartSeries(): array
    {
        return [
            self::ROSE_600,
            self::GOLD_500,
            self::ROSE_800,
            self::ROSE_400,
            self::GOLD_600,
            self::ROSE_700,
            self::GOLD_300,
            self::ROSE_500,
            self::ROSE_900,
            self::GOLD_400,
        ];
    }
}
