<?php

namespace App\Filament\Resources\Servers\Pages;

use App\Filament\Resources\Servers\ServerResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListServers extends ListRecords
{
    protected static string $resource = ServerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make(),

            'active' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) =>
                $query->where('status', true)
                ),

            'inactive' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) =>
                $query->where('status', false)
                ),

            'free Servers' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) =>
                $query->where('ispro', false)
                ),

            'Premium Servers' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) =>
                $query->where('ispro', true)
                ),

            'Recommended Servers' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) =>
                $query->where('is_recommended', true)
                ),
        ];
    }
}
