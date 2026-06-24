<?php

namespace App\Filament\Resources\Members\Widgets;

use App\Models\User as Member;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class MemberOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $query = Member::query();

        return [
            Stat::make('Total Kader Aktif', (clone $query)->where('status', 'Aktif')->count())
                ->description('Jumlah anggota berstatus aktif')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success'),
            Stat::make('Registrasi Pending', (clone $query)->where('status', 'Pending')->count())
                ->description('Menunggu verifikasi admin')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
            Stat::make('Total Nonaktif', (clone $query)->where('status', 'Nonaktif')->count())
                ->description('Jumlah anggota nonaktif')
                ->descriptionIcon('heroicon-m-minus-circle')
                ->color('danger'),
        ];
    }
}
