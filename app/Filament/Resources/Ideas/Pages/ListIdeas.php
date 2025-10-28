<?php

namespace App\Filament\Resources\Ideas\Pages;

use App\Filament\Resources\Ideas\IdeasResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListIdeas extends ListRecords
{
    protected static string $resource = IdeasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
