<?php

namespace App\Filament\Resources\Ideas;

use App\Filament\Resources\Ideas\Pages\CreateIdeas;
use App\Filament\Resources\Ideas\Pages\EditIdeas;
use App\Filament\Resources\Ideas\Pages\ListIdeas;
use App\Filament\Resources\Ideas\Pages\ViewIdeas;
use App\Filament\Resources\Ideas\Schemas\IdeasForm;
use App\Filament\Resources\Ideas\Schemas\IdeasInfolist;
use App\Filament\Resources\Ideas\Tables\IdeasTable;
use App\Models\Ideas;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use App\Filament\Resources\Ideas\RelationManagers\PitchesRelationManager;
use App\Filament\Resources\Ideas\RelationManagers\ValidationRelationManager;

class IdeasResource extends Resource
{
    protected static ?string $model = Ideas::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedLightBulb;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return IdeasForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return IdeasInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return IdeasTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            PitchesRelationManager::class,
            ValidationRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListIdeas::route('/'),
            // 'create' => CreateIdeas::route('/create'),
            // 'view' => ViewIdeas::route('/{record}'),
            // 'edit' => EditIdeas::route('/{record}/edit'),
        ];
    }

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-light-bulb'; 
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('user_id', Auth::id());
    }

}
