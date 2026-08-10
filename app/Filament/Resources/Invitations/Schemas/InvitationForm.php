<?php

namespace App\Filament\Resources\Invitations\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class InvitationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Undangan Aktif')
                ->columns(2)
                ->schema([
                    Select::make('invitation_order_id')
                        ->label('Order')
                        ->relationship('order', 'order_code')
                        ->searchable()
                        ->preload()
                        ->required(),
                    Select::make('user_id')
                        ->label('Owner')
                        ->relationship('user', 'name')
                        ->searchable()
                        ->preload()
                        ->required(),
                    Select::make('invitation_template_id')
                        ->label('Template')
                        ->relationship('template', 'name')
                        ->searchable()
                        ->preload()
                        ->required(),
                    TextInput::make('slug')->required()->unique(ignoreRecord: true),
                    TextInput::make('subdomain')->unique(ignoreRecord: true),
                    TextInput::make('custom_domain')->unique(ignoreRecord: true),
                    TextInput::make('views_count')->numeric()->default(0)->disabled(),
                    TextInput::make('rsvp_yes')->numeric()->default(0)->disabled(),
                    TextInput::make('rsvp_no')->numeric()->default(0)->disabled(),
                    TextInput::make('rsvp_maybe')->numeric()->default(0)->disabled(),
                    Toggle::make('is_published')->label('Published'),
                ]),
        ]);
    }
}
