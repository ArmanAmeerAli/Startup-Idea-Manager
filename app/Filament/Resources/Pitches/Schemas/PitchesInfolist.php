<?php

namespace App\Filament\Resources\Pitches\Schemas;

use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;

class PitchesInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('idea.title')
                    ->label('Idea'),
                TextEntry::make('pitch_title')
                    ->label('Pitch Title'),
                TextEntry::make('pitch_points')
                    ->label('Pitch Points')
                    ->listWithLineBreaks(),
                TextEntry::make('pitch_text')
                    ->label('Pitch Text'),
                TextEntry::make('status')
                    ->label('Status')
                    ->badge(),
                TextEntry::make('created_at')
                    ->label('Created At')
                    ->date(),
                TextEntry::make('updated_at')
                    ->label('Updated At')
                    ->date(),
            ]);
    }
}
