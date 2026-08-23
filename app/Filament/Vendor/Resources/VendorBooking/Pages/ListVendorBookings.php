<?php

namespace App\Filament\Vendor\Resources\VendorBooking\Pages;

use App\Filament\Vendor\Resources\VendorBooking\VendorBookingResource;
use Filament\Resources\Pages\ListRecords;

class ListVendorBookings extends ListRecords
{
    protected static string $resource = VendorBookingResource::class;
}