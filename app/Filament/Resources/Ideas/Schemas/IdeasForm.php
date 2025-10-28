<?php

namespace App\Filament\Resources\Ideas\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use App\Filament\Resources\Ideas\Pages\ViewIdeas;
use App\Filament\Resources\Ideas\Pages\EditIdeas;

class IdeasForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Textarea::make('description')
                    ->required()
                    ->rows(4),
                TextInput::make('category')
                    ->maxLength(255),
                Select::make('status')
                    ->label('Status')
                    ->options([
                        'Pending' => 'Pending',
                        'Validated' => 'Validated',
                        'Rejected' => 'Rejected',
                    ])
                    ->default('pending')
                    ->disabled()
                    //i want to keep it hidden when i create a new idea
                    ->visible(fn($get, $livewire) => ($livewire instanceof ViewIdeas || $livewire instanceof EditIdeas)),
                TextInput::make('ai_score')
                    ->label('AI Score')
                    ->disabled()
                    ->visible(fn($get, $livewire) => ($livewire instanceof ViewIdeas || $livewire instanceof EditIdeas) && filled($get('ai_score')))
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100),
                Textarea::make('ai_feedback')
                    ->label('AI Feedback')
                    ->disabled()
                    ->rows(3)
                    ->visible(fn($get, $livewire) => ($livewire instanceof ViewIdeas || $livewire instanceof EditIdeas) && filled($get('ai_feedback'))),
            ]);
    }
}
