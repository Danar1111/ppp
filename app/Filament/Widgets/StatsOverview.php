<?php

namespace App\Filament\Widgets;

use App\Models\User as Member;
use App\Models\Committee;
use App\Models\Event;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = -3; // Ensure stats appear at the VERY TOP

    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        return [
            Stat::make('Total Anggota', Member::whereDoesntHave('roles')->count())
                ->description('Jumlah seluruh anggota terdaftar')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),
            Stat::make('Total Pengurus', Committee::count())
                ->description('Jumlah fungsionaris & pengurus')
                ->descriptionIcon('heroicon-m-identification')
                ->color('primary'),
            Stat::make('Agenda Mendatang', Event::where('start_datetime', '>=', now())->count())
                ->description('Kegiatan yang akan datang')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('warning'),
        ];
    }
}
