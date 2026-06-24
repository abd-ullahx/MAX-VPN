<?php

namespace App\Filament\Resources\Servers\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;

class ServerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('country_id')->relationship('country', 'name')->required()->searchable(),
                TextInput::make('username')->required(),
                TextInput::make('password')->required(),
                TextInput::make('ip_address')->label('Ip Address ')->required(),
                TextInput::make('priority')->label('Priority '),
                TextInput::make('state')->label('State '),
                TextInput::make('max_connection')->label('Max Connection ')->columnSpan(2),
                Textarea::make('ovpn')->label('OVPN ')->columnSpan(2),

                Toggle::make('ispro')->label('Is Premium ')->default(false),
                Toggle::make('status')->label('Status ')->default(true),
                Toggle::make('is_recommended')->label('Recommended ')->default(true),
            ]);
    }
}
