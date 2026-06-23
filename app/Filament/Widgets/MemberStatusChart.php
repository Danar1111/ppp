<?php

namespace App\Filament\Widgets;

use App\Models\User as Member;
use Filament\Widgets\ChartWidget;

class MemberStatusChart extends ChartWidget
{
    protected ?string $heading = 'Status Keanggotaan';

    protected static ?int $sort = -1; // Below stats, below welcome

    protected int | string | array $columnSpan = 1;

    protected ?string $maxHeight = '280px';

    protected function getData(): array
    {
        $aktif = Member::whereDoesntHave('roles')->where('status', 'Aktif')->count();
        $pending = Member::whereDoesntHave('roles')->where('status', 'Pending')->count();
        $nonaktif = Member::whereDoesntHave('roles')->where('status', 'Nonaktif')->count();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Anggota',
                    'data' => [$aktif, $pending, $nonaktif],
                    'backgroundColor' => [
                        '#005B2B', // Forest Green (Aktif)
                        '#D97706', // Golden Yellow (Pending)
                        '#EF4444', // Red (Nonaktif)
                    ],
                ],
            ],
            'labels' => ['Aktif', 'Pending', 'Nonaktif'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                    'labels' => [
                        'padding' => 16,
                        'usePointStyle' => true,
                        'pointStyle' => 'circle',
                        'font' => [
                            'size' => 12,
                            'family' => 'Plus Jakarta Sans',
                        ],
                    ],
                ],
            ],
            'cutout' => '65%',
            'maintainAspectRatio' => false,
        ];
    }
}
