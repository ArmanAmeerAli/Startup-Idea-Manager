<?php

namespace App\Filament\Resources\Ideas\RelationManagers;

use App\Filament\Resources\Pitches\PitchesResource;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use App\Filament\Resources\Pitches\Pages\EditPitches;
use App\Filament\Resources\Pitches\Pages\ViewPitches;

class PitchesRelationManager extends RelationManager
{
    protected static string $relationship = 'Pitches';

    protected static ?string $relatedResource = PitchesResource::class;

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
                TextColumn::make('pitch_title')
                    ->label('Pitch Title')
                    ->searchable()
                    ->sortable(),
                // TextColumn::make('pitch_points')
                //     ->label('Pitch Points')
                //     ->searchable()
                //     ->sortable(),
                // TextColumn::make('pitch_text')
                //     ->label('Pitch Text')
                //     ->searchable()
                //     ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Created At')
                    ->disabled()
                    ->date('Y-m-d'),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('pitch_title')
                    ->label('Pitch Title')
                    ->required()
                    ->maxLength(255),
                TextInput::make('pitch_points')
                    ->label('Pitch Points')
                    ->required()
                    ->maxLength(255),
                TextInput::make('pitch_text')
                    ->label('Pitch')
                    ->required()
                    ->maxLength(255),
                Select::make('status')
                    ->label('Status')
                    ->required()
                    ->disabled()
                    ->options([
                        'draft' => 'Draft',
                        'generated' => 'Generated',
                        'approved' => 'Approved',
                        'published' => 'Published',
                    ])
                    ->default('draft')
                    ->visible(fn($get, $livewire) => ($livewire instanceof ViewPitches || $livewire instanceof EditPitches)),
            ]);
    }
}
