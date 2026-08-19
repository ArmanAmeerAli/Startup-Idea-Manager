<?php

namespace App\Filament\Resources\Ideas\Tables;

use App\Models\Ideas;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\TagsInput;
use Carbon\Carbon;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter as TableFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class IdeasTable
{
    public static function getColumns(): array
    {
        return [
            TextColumn::make('title')
                ->label('Title')
                ->searchable()
                ->sortable(),
            TextColumn::make('status')
                ->label('Status')
                ->badge()
                ->colors([
                    'warning' => 'pending',
                    'success' => 'validated',
                    'info' => 'pitched',
                ])
                ->searchable()
                ->sortable(),
            TextColumn::make('category')
                ->label('Category')
                ->searchable()
                ->sortable(),
            TextColumn::make('created_at')
                ->label('Created At')
                ->date('M d Y')
                ->searchable()
                ->sortable(),
        ];
    }

    public static function getFilters(): array
    {
        return [
            SelectFilter::make('status')
                ->label('Status')
                ->options([
                    'pending' => 'Pending',
                    'validated' => 'Validated',
                    'pitched' => 'Pitched',
                ])
                ->native(false),
            SelectFilter::make('category')
                ->label('Category')
                ->multiple()
                ->options(function () {
                    return Ideas::where('user_id', Auth::id())
                        ->pluck('category')
                        ->flatten()       // flatten arrays into one list
                        ->unique()
                        ->sort()
                        ->mapWithKeys(fn($value) => [$value => $value])
                        ->toArray();
                })
                ->query(function ($query, $data) {
                    if (! empty($data['values'])) {
                        foreach ($data['values'] as $category) {
                            $query->whereJsonContains('category', $category);
                        }
                    }
            }),
            TableFilter::make('created_at')
                ->label('Date Added')
                ->form([
                    DatePicker::make('created_from')
                        ->label('From'),
                    DatePicker::make('created_until')
                        ->label('Until'),
                ])
                ->indicateUsing(function (array $data): array {
                    $indicators = [];

                    if ($data['created_from'] ?? null) {
                        $indicators['created_from'] = 'From: ' . Carbon::parse($data['created_from'])->toFormattedDateString();
                    }

                    if ($data['created_until'] ?? null) {
                        $indicators['created_until'] = 'Until: ' . Carbon::parse($data['created_until'])->toFormattedDateString();
                    }
                    return $indicators;
                })
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            $data['created_from'] ?? null,
                            fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date)
                        )
                        ->when(
                            $data['created_until'] ?? null,
                            fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date)
                        );
                }),
        ];
    }

    public static function getActions(): array
    {
        return [
            ViewAction::make(),
            EditAction::make()->slideOver(),
            DeleteAction::make(),
        ];
    }

    public static function getBulkActions(): array
    {
        return [
            BulkActionGroup::make([
                DeleteBulkAction::make(),
            ]),
        ];
    }

    public static function configure(Table $table): Table
    {
        return $table
            ->columns(self::getColumns())
            ->filters(self::getFilters())
            ->actions(self::getActions())
            ->bulkActions(self::getBulkActions());
    }
}
