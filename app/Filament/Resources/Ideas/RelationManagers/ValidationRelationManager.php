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
use App\Filament\Resources\Validations\Pages\EditValidations;
use App\Filament\Resources\Validations\Pages\ViewValidations;

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
                CreateAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['idea_id'] = $this->getOwnerRecord()->id;
                        return $data;
                    }),
            ])
            ->columns([
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
