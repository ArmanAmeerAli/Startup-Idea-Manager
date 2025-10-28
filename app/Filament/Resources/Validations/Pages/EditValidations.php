<?php

namespace App\Filament\Resources\Validations\Pages;

use App\Filament\Resources\Validations\ValidationsResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditValidations extends EditRecord
{
    protected static string $resource = ValidationsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
