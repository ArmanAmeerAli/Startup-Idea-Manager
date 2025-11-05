<?php

namespace App\Filament\Widgets;

use App\Models\Validations;
use Filament\Widgets\ChartWidget;

class ValidationsCompletion extends ChartWidget
{
    protected ?string $heading = 'Validations Status Overview';
    protected static ?int $sort = 4;
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
        // Get all validations
        $validations = Validations::all();
        
        // Group validations by status
        $statusCounts = $validations->groupBy('status')->map->count();
        
        // Get counts for each status
        $pending = $statusCounts->get('pending', 0);
        $approved = $statusCounts->get('approved', 0);
        $rejected = $statusCounts->get('rejected', 0);
        
        $totalValidations = $pending + $approved + $rejected;
        
        if ($totalValidations === 0) {
            return [
                'datasets' => [
                    [
                        'label' => 'No validations found',
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
                    'label' => 'Validations',
                    'data' => [$pending, $approved, $rejected],
                    'backgroundColor' => [
                        '#F59E0B', // Amber-500 for Pending
                        '#10B981', // Green-500 for Approved
                        '#EF4444'  // Red-500 for Rejected
                    ],
                    'borderColor' => [
                        '#F59E0B',
                        '#10B981',
                        '#EF4444'
                    ],
                    'borderWidth' => 1,
                ],
            ],
            'labels' => ['Pending', 'Approved', 'Rejected'],
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