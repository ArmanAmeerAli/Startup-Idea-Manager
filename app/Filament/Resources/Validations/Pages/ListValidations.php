<?php

namespace App\Filament\Resources\Validations\Pages;

use App\Filament\Resources\Validations\ValidationsResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListValidations extends ListRecords
{
    protected static string $resource = ValidationsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
