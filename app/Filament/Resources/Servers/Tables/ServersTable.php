<?php

namespace App\Filament\Resources\Servers\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class ServersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('country.name')->label('Country Name')->searchable()->sortable(),
                TextColumn::make('ip_address')->label('Ip Address')->sortable(),
                TextColumn::make('active_connection')->label('Active Connection')->sortable(),
                TextColumn::make('max_connection')->label('Max Connection')->sortable(),
                TextColumn::make('count_updated_at')->label('Last Count Updated At')
                    ->isoTime('LLL')
                    ->placeholder('N/A')
                    ->sortable(),
                
                TextColumn::make('country.flag')->label('Country Flag')->searchable(),
                BooleanColumn::make('ispro')->sortable(),
                BooleanColumn::make('status')->sortable(),
                BooleanColumn::make('is_recommended')->sortable(),

                TextColumn::make('created_at')->label('Created At')
                    ->isoTime('LL')
                    ->placeholder('N/A'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->paginated([10, 25, 50, 100])
            ->defaultPaginationPageOption(25);
    }
}
