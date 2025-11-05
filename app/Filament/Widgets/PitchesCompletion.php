<?php

namespace App\Filament\Widgets;

use App\Models\Pitches;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PitchesCompletion extends ChartWidget
{
    protected ?string $heading = 'Pitches Status Overview';
    protected static ?int $sort = 3;
    protected ?string $maxHeight = '400px';
    protected static string $maxWidth = 'md';

    protected function getExtraAttributes(): array
    {
        return [
            'class' => 'w-full mx-auto',
            'style' => 'max-width: 600px;',
        ];
    }

    protected function getData(): array
    {
        // Get all pitches (temporarily removing user filter since user_id is missing)
        $pitches = Pitches::all();
        
        // Group pitches by status
        $statusCounts = $pitches->groupBy('status')->map->count();
        
        // Get counts for each status, defaulting to 0 if not present
        $draft = $statusCounts->get('draft', 0);
        $generated = $statusCounts->get('generated', 0);
        $approved = $statusCounts->get('approved', 0);
        $rejected = $statusCounts->get('rejected', 0);
        
        $totalPitches = $draft + $generated + $approved + $rejected;
        
        if ($totalPitches === 0) {
            // Return empty data with a message
            return [
                'datasets' => [
                    [
                        'label' => 'No pitches found',
                        'data' => [1],
                        'backgroundColor' => ['#E5E7EB'],
                        'borderColor' => ['#9CA3AF'],
                        'borderWidth' => 1,
                    ],
                ],
                'labels' => ['No data available'],
            ];
        }
        
        return [
            'datasets' => [
                [
                    'label' => 'Pitches',
                    'data' => [ $draft, $generated, $approved, $rejected ],
                    'backgroundColor' => [
                        '#9CA3AF', // Gray-400 for Draft
                        '#60A5FA', // Blue-400 for Generated
                        '#34D399', // Green-400 for Approved
                        '#F87171'  // Red-400 for Rejected
                    ],
                    'borderColor' => [
                        '#9CA3AF',
                        '#60A5FA',
                        '#34D399',
                        '#F87171'
                    ],
                    'borderWidth' => 1,
                ],
            ],
            'labels' => ['Draft', 'Generated', 'Approved', 'Rejected'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): ?array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => true,
            'cutout' => '65%',
            'spacing' => 0,
            'layout' => [
                'padding' => [
                    'top' => 5,
                    'right' => 5,
                    'bottom' => 5,
                    'left' => 5
                ]
            ],
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                    'labels' => [
                        'padding' => 6,
                        'boxWidth' => 12,
                        'usePointStyle' => true,
                        'pointStyle' => 'circle',
                    ],
                ],
                'tooltip' => [
                    'enabled' => true,
                    'backgroundColor' => 'rgba(0, 0, 0, 0.8)',
                    'titleFont' => ['size' => 12],
                    'bodyFont' => ['size' => 13],
                    'padding' => 8,
                    'cornerRadius' => 4,
                ],
            ],
            'animation' => [
                'duration' => 1000,
                'easing' => 'easeInOutQuart',
            ],
        ];
    }
}
