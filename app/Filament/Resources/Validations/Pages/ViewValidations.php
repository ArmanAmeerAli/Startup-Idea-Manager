<?php

namespace App\Filament\Resources\Validations\Pages;

use App\Filament\Resources\Validations\ValidationsResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewValidations extends ViewRecord
{
    protected static string $resource = ValidationsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
