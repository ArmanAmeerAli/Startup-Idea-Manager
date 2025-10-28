<?php

namespace App\Filament\Resources\Pitches\Pages;

use App\Filament\Resources\Pitches\PitchesResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditPitches extends EditRecord
{
    protected static string $resource = PitchesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
