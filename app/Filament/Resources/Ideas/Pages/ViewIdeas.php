<?php

namespace App\Filament\Resources\Ideas\Pages;

use App\Filament\Resources\Ideas\IdeasResource;
use App\Filament\Resources\Ideas\RelationManagers\PitchesRelationManager;
use App\Filament\Resources\Ideas\RelationManagers\ValidationRelationManager;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewIdeas extends ViewRecord
{
    protected static string $resource = IdeasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }

    protected function getRelations():array
    {
        return [
            PitchesRelationManager::class,
            ValidationRelationManager::class,
        ];
    }
}
