<?php

namespace App\Filament\Resources\Validations;

use App\Filament\Resources\Validations\Pages\CreateValidations;
use App\Filament\Resources\Validations\Pages\EditValidations;
use App\Filament\Resources\Validations\Pages\ListValidations;
use App\Filament\Resources\Validations\Pages\ViewValidations;
use App\Filament\Resources\Validations\Schemas\ValidationsForm;
use App\Filament\Resources\Validations\Schemas\ValidationsInfolist;
use App\Filament\Resources\Validations\Tables\ValidationsTable;
use App\Models\Validations;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ValidationsResource extends Resource
{
    protected static ?string $model = Validations::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCheckBadge;

    public static function form(Schema $schema): Schema
    {
        return ValidationsForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ValidationsInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ValidationsTable::configure($table);
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
            'index' => ListValidations::route('/'),
            // 'create' => CreateValidations::route('/create'),
            // 'view' => ViewValidations::route('/{record}'),
            // 'edit' => EditValidations::route('/{record}/edit'),
        ];
    }

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-check-badge';
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereHas('idea', function (Builder $query) {
                $query->where('user_id', Auth::id());
            });
    }
}
