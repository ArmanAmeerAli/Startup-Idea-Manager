<?php

namespace App\Filament\Resources\Pitches\Pages;

use App\Filament\Resources\Pitches\PitchesResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPitches extends ViewRecord
{
    protected static string $resource = PitchesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
