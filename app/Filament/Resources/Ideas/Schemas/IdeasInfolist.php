<?php

namespace App\Filament\Resources\Ideas\Schemas;

use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;

class IdeasInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('title')
                    ->label('Title'),
                TextEntry::make('description')
                    ->label('Description'),
                TextEntry::make('category')
                    ->label('Category'),
                TextEntry::make('status')
                    ->badge()
                    ->label('Status'),
                TextEntry::make('ai_score')
                    ->label('AI Score'),
                TextEntry::make('ai_feedback')
                    ->label('AI Feedback'),
                TextEntry::make('created_at')
                    ->date()
                    ->label('Created At'),
                TextEntry::make('updated_at')
                    ->date()
                    ->label('Updated At'),
            ]);
    }
}
