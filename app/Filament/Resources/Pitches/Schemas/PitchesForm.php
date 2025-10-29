<?php

namespace App\Filament\Resources\Pitches\Schemas;

use App\Filament\Resources\Pitches\Pages\EditPitches;
use App\Filament\Resources\Pitches\Pages\ViewPitches;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use App\Models\Ideas;

class PitchesForm
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
                TextInput::make('pitch_title')
                    ->label('Pitch Title')
                    ->required(),
                Textarea::make('pitch_points')
                    ->label('Pitch Points')
                    ->required()
                    ->rows(3),
                Textarea::make('pitch_text')
                    ->label('Pitch Text')
                    ->required()
                    ->rows(6),
                Select::make('status')
                    ->label('Status')
                    ->options([
                        'draft' => 'Draft',
                        'generated' => 'Generated',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ])
                    ->default('draft')
                    ->visible(fn($get, $livewire) => ($livewire instanceof ViewPitches || $livewire instanceof EditPitches))
                    ->disabled(),
            ]);
    }
}
