<?php

namespace App\Filament\Widgets;

use App\Models\Ideas;
use App\Models\Pitches;
use App\Models\Validations;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class AllIdeas extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $user = Auth::user();
        $userIdeas = Ideas::where('user_id', $user->id)->pluck('id');
        $totalIdeas = $userIdeas->count();
        $totalPitches = Pitches::whereIn('idea_id', $userIdeas)->count();
        $totalValidations = Validations::whereIn('idea_id', $userIdeas)->count();

        return [
            Stat::make('Total Ideas', $totalIdeas)
                ->description('This Much Ideas You Have')
                ->icon('heroicon-o-light-bulb')
                ->color('success'),
            Stat::make('Total Pitches', $totalPitches)
                ->description('This Much Pitches You Have')
                ->icon('heroicon-o-microphone')
                ->color('warning'),
            Stat::make('Total Validations', $totalValidations)
                ->description('This Much Validations You Have')
                ->icon('heroicon-o-check-circle')
                ->color('danger'),
        ];
    }
}
