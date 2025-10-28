<?php

namespace App\Filament\Resources\Ideas\Pages;

use App\Filament\Resources\Ideas\IdeasResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditIdeas extends EditRecord
{
    protected static string $resource = IdeasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
