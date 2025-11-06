<?php

namespace App\Filament\Widgets;

use App\Models\Ideas;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class IdeasCompletion extends ChartWidget
{
    protected ?string $heading = 'Ideas Status Overview';
    protected static ?int $sort = 2;
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
        $user = Auth::user();
        // Get the count of Ideas by status for the authenticated user
        $pending = Ideas::where('user_id', $user->id)->where('status', 'pending')->count();
        $processing = Ideas::where('user_id', $user->id)->where('status', 'processing')->count();
        $completed = Ideas::where('user_id', $user->id)->where('status', 'completed')->count();
        $rejected = Ideas::where('user_id', $user->id)->where('status', 'rejected')->count();

        $totalIdeas = $pending + $processing + $completed + $rejected;

        if ($totalIdeas === 0) {
            // Return empty data with a message
            return [
                'datasets' => [
                    [
                        'label' => 'No ideas found',
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
                    'label' => 'Ideas',
                    'data' => [$pending, $processing, $completed, $rejected],
                    'backgroundColor' => [
                        'rgba(245, 158, 11, 0.9)', 
                        'rgba(59, 130, 246, 0.9)', 
                        'rgba(16, 185, 129, 0.9)', 
                        'rgba(239, 68, 68, 0.9)'  
                    ],
                    'borderColor' => [
                        'rgba(245, 158, 11, 1)', 
                        'rgba(59, 130, 246, 1)', 
                        'rgba(16, 185, 129, 1)', 
                        'rgba(239, 68, 68, 1)'  
                    ],
                    'borderWidth' => 1,
                ],
            ],
            'labels' => ['Pending', 'Processing', 'Completed', 'Rejected'],
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
            'elements' => [
                'arc' => [
                    'borderWidth' => 6,
                    'borderColor' => 'rgba(0, 0, 0, 0.18)', // Shadow border
                    'shadowOffsetX' => 5,
                    'shadowOffsetY' => 6,
                    'shadowBlur' => 12,
                    'shadowColor' => 'rgba(0,0,0,0.25)',
                ],
            ],
        ];
    }
}
