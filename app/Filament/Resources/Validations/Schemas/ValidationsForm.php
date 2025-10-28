<?php

namespace App\Filament\Resources\Validations\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use App\Filament\Resources\Validations\Pages\ViewValidations;
use App\Filament\Resources\Validations\Pages\EditValidations;

class ValidationsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('idea_id')
                    ->label('Idea')
                    ->relationship('idea', 'title')
                    ->required(),
                TextInput::make('ai_model')
                    ->label('AI Model')
                    ->default('-')
                    ->maxLength(255)
                    ->required(),
                TextInput::make('ai_score')
                    ->label('AI Score')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100)
                    ->nullable(),
                Textarea::make('ai_feedback')
                    ->label('AI Feedback')
                    ->rows(3)
                    ->nullable(),
                Textarea::make('ai_suggestions')
                    ->label('AI Suggestions')
                    ->rows(3)
                    ->nullable(),
                Select::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ])
                    ->default('pending')
                    ->disabled()
                    ->visible(fn($get, $livewire) => ($livewire instanceof ViewValidations || $livewire instanceof EditValidations)),
            ]);
    }
}
