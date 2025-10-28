<?php

namespace App\Filament\Resources\Pitches\Pages;

use App\Filament\Resources\Pitches\PitchesResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPitches extends ListRecords
{
    protected static string $resource = PitchesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
