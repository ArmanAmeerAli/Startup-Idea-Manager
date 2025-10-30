<?php

namespace App\Filament\Resources\Ideas\RelationManagers;

use App\Filament\Resources\Validations\ValidationsResource;
use Filament\Actions\CreateAction;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ValidationRelationManager extends RelationManager
{
    protected static string $relationship = 'validation';

    protected static ?string $relatedResource = ValidationsResource::class;

    public function isReadOnly(): bool
    {
        return false;
    }

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ])
            ->columns([
                TextColumn::make('idea.title')
                    ->label('Idea')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('ai_score')
                    ->label('Score')
                    ->searchable()
                    ->color(fn(int $state): string => $state >= 70 ? 'success' : ($state >= 40 ? 'warning' : 'danger'))
                    ->sortable(),
                TextColumn::make('ai_model')
                    ->label('Model')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'approved',
                        'danger' => 'rejected',
                    ])
                    ->searchable()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime('d M Y')
                    ->searchable()
                    ->sortable(),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make()->slideOver(),
                DeleteAction::make(),
            ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('score')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100)
                    ->label('Score'),

                Textarea::make('feedback')
                    ->label('Feedback')
                    ->rows(3),

                Textarea::make('suggestions')
                    ->label('Suggestions')
                    ->rows(3),
            ]);
    }
}
