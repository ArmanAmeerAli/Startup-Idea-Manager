<?php

namespace App\Filament\Resources\Ideas\Tables;

use App\Models\Ideas;
use Filament\Tables\Filters\Filter;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class IdeasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
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
            ])
            ->filters([
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
                    ->native(false)
                    ->options( fn() => Ideas::query()
                        ->where('user_id', Auth::id())
                        ->whereNotNull('category')
                        ->pluck('category', 'category')
                        ->toArray()
                    )
                    ->searchable(),
                SelectFilter::make('title')
                    ->label('Title')
                    ->native(false)
                    ->options( fn() => Ideas::query()
                        ->where('user_id', Auth::id())
                        ->whereNotNull('title')
                        ->distinct()
                        ->pluck('title', 'title')
                        ->toArray()
                    )
                    ->searchable(),
                Filter::make('created_at')
                    ->label('Date Added')
                    ->form([
                        DatePicker::make('created_from')->label('From'),
                        DatePicker::make('created_until')->label('Until'),
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
                    ->query(function ($query, array $data) {
                        return $query
                            ->when(
                                $data['created_from'] ?? null,
                                function ($q, $date) {
                                    return $q->whereDate('created_at' , '>=' , $date);
                                }
                            )
                            ->when(
                                $data['created_until'] ?? null,
                                function ($q, $date) {
                                    return $q->whereDate('created_at' , '<=' , $date);
                                }
                            );
                    }),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make()->slideOver(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
