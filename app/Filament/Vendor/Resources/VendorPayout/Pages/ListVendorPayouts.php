<?php

namespace App\Filament\Vendor\Resources\VendorPayout\Pages;

use App\Filament\Vendor\Resources\VendorPayout\VendorPayoutResource;
use Filament\Resources\Pages\ListRecords;

class ListVendorPayouts extends ListRecords
{
    protected static string $resource = VendorPayoutResource::class;
}