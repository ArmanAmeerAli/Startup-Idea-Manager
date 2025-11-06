<?php

namespace App\Filament\Resources\Ideas\Schemas;

use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use App\Filament\Resources\Ideas\Pages\ViewIdeas;
use App\Filament\Resources\Ideas\Pages\EditIdeas;
use Illuminate\Support\Facades\DB;

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
                //i want to pick existing category from the database, which are already registered in the database
                //i dont have its model, i just want to pick it from the database
                TagsInput::make('category')
                    ->label('Category')
                    ->suggestions(
                        collect(
                            DB::table('ideas')
                                ->whereNotNull('category')
                                ->pluck('category')   // returns JSON arrays
                        )
                            ->flatMap(fn($item) => json_decode($item, true)) // expand arrays
                            ->filter()                                         // remove nulls
                            ->unique()                                         // remove duplicates
                            ->values()                                         // reset keys
                            ->toArray()
                    )
                    ->placeholder('Type or Choose Category')
                    ->required(),
                Select::make('status')
                    ->label('Status')
                    ->options([
                        'Pending' => 'Pending',
                        'Processing' => 'Processing',
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
