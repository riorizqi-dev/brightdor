<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Bookings\BookingResource;
use App\Filament\Resources\InvitationTemplates\InvitationTemplateResource;
use App\Filament\Resources\Payouts\PayoutResource;
use App\Filament\Resources\Vendors\VendorResource;
use Filament\Widgets\Widget;

class QuickActions extends Widget
{
    protected string $view = 'filament.widgets.quick-actions';

    protected static ?int $sort = 4;

    protected int | string | array $columnSpan = [
        'default' => 'full',
        'lg' => 4,
    ];

    protected static bool $isLazy = false;

    /**
     * @return array<int, array{label: string, description: string, url: string, icon: string}>
     */
    public function getActions(): array
    {
        return [
            [
                'label' => __('brightdor.dashboard.review_vendors'),
                'description' => __('brightdor.dashboard.review_vendors_desc'),
                'url' => VendorResource::getUrl('index'),
                'icon' => 'vendor',
            ],
            [
                'label' => __('brightdor.dashboard.manage_bookings'),
                'description' => __('brightdor.dashboard.manage_bookings_desc'),
                'url' => BookingResource::getUrl('index'),
                'icon' => 'booking',
            ],
            [
                'label' => __('brightdor.dashboard.invitation_templates'),
                'description' => __('brightdor.dashboard.invitation_templates_desc'),
                'url' => InvitationTemplateResource::getUrl('index'),
                'icon' => 'invite',
            ],
            [
                'label' => __('brightdor.dashboard.vendor_payouts'),
                'description' => __('brightdor.dashboard.vendor_payouts_desc'),
                'url' => PayoutResource::getUrl('index'),
                'icon' => 'payout',
            ],
        ];
    }
}
