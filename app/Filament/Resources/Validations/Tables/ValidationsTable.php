<?php

namespace App\Filament\Resources\Validations\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Facades\Auth;
use App\Models\Ideas;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Carbon\Carbon;
use App\Models\Validations;
use Illuminate\Database\Eloquent\Builder;

class ValidationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('idea.title')
                    ->label('Idea')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('ai_score')
                    ->label('Score')
                    ->numeric()
                    ->sortable()
                    ->searchable(),
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
                    ->sortable()
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime('d M Y')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ])
                    ->native(false),
                SelectFilter::make('idea_id')
                    ->label('Idea')
                    ->native(false)
                    ->options(
                        fn() => Ideas::where('user_id', Auth::id())
                            ->pluck('title', 'id')
                            ->toArray(),
                    )
                    ->searchable()
                    ->query(function (Builder $query, array $data) {
                        $value = $data['value'] ?? null;

                        if (! empty($value)) {
                            $query->where('idea_id', $value);
                        }
                    }),
                SelectFilter::make('ai_model')
                    ->label('Ai Model')
                    ->native(false)
                    ->options( fn() => Validations::query()
                        ->whereHas('idea', fn($q) => $q->where('user_id', Auth::id()))
                        ->whereNotNull('ai_model')
                        ->distinct()
                        ->pluck('ai_model', 'ai_model')
                        ->toArray(),
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
                                    return $q->whereDate('created_at', '>=', $date);
                                }
                            )
                            ->when(
                                $data['created_until'] ?? null,
                                function ($q, $date) {
                                    return $q->whereDate('created_at', '<=', $date);
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
