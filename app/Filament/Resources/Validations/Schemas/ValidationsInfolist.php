<?php

namespace App\Filament\Resources\Validations\Schemas;

use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;

class ValidationsInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('idea.title')
                    ->label('Idea'),
                TextEntry::make('ai_model')
                    ->label('AI Model'),
                TextEntry::make('ai_score')
                    ->label('AI Score'),
                TextEntry::make('ai_feedback')
                    ->label('AI Feedback'),
                TextEntry::make('ai_suggestions')
                    ->label('AI Suggestions')
                    ->listWithLineBreaks(),
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
