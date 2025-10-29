<?php

namespace App\Filament\Resources\Validations\Schemas;

use App\Filament\Resources\Validations\Pages\EditValidations;
use App\Filament\Resources\Validations\Pages\ViewValidations;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;
use App\Models\Ideas;

class ValidationsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('idea_id')
                    ->label('Idea')
                    ->options(fn () => Ideas::query()
                        ->where('user_id', Auth::id())
                        ->orderBy('title', 'asc')
                        ->pluck('title', 'id'))
                    ->searchable()
                    ->preload()
                    ->native(false)
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
