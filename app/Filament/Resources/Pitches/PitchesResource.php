<?php

namespace App\Filament\Resources\Pitches;

use App\Filament\Resources\Pitches\Pages\CreatePitches;
use App\Filament\Resources\Pitches\Pages\EditPitches;
use App\Filament\Resources\Pitches\Pages\ListPitches;
use App\Filament\Resources\Pitches\Pages\ViewPitches;
use App\Filament\Resources\Pitches\Schemas\PitchesForm;
use App\Filament\Resources\Pitches\Schemas\PitchesInfolist;
use App\Filament\Resources\Pitches\Tables\PitchesTable;
use App\Models\Pitches;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PitchesResource extends Resource
{
    protected static ?string $model = Pitches::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return PitchesForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PitchesInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PitchesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPitches::route('/'),
            // 'create' => CreatePitches::route('/create'),
            // 'view' => ViewPitches::route('/{record}'),
            // 'edit' => EditPitches::route('/{record}/edit'),
        ];
    }
}
