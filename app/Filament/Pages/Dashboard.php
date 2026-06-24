<?php

namespace App\Filament\Pages;

use App\Models\Committee;
use App\Models\Event;
use App\Models\User as Member;
use BackedEnum;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Page
{
    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-home';

    protected string $view = 'filament.pages.dashboard';

    protected static ?int $navigationSort = -2;

    protected static ?string $title = 'Dasbor';

    public function getTitle(): string|Htmlable
    {
        return 'Dasbor';
    }

    public function getTotalAnggota(): int
    {
        return Member::count();
    }

    public function getTotalPengurus(): int
    {
        return Committee::count();
    }

    public function getAgendaMendatang(): int
    {
        return Event::where('start_datetime', '>=', now())->count();
    }

    public function getAnggotaAktif(): int
    {
        return Member::where('status', 'Aktif')->count();
    }

    public function getAnggotaPending(): int
    {
        return Member::where('status', 'Pending')->count();
    }

    public function getAnggotaNonaktif(): int
    {
        return Member::where('status', 'Nonaktif')->count();
    }

    public function getRecentActivities(): Collection
    {
        return Member::query()
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($member) {
                $initials = collect(explode(' ', $member->name))
                    ->take(2)
                    ->map(fn($part) => strtoupper(substr($part, 0, 1)))
                    ->join('');
                return [
                    'initials' => $initials,
                    'name' => $member->name,
                    'role' => 'Anggota ' . ($member->status ?? 'Terdaftar'),
                    'action' => 'Data anggota diperbarui',
                    'time' => $member->updated_at->diffForHumans(),
                    'status' => $member->status ?? 'Aktif',
                ];
            });
    }

    public function getChartData(): array
    {
        $total = $this->getTotalAnggota();
        if ($total === 0) {
            return [
                'total' => 0, 'aktif' => 0, 'pending' => 0, 'nonaktif' => 0,
                'circumference' => 502.65,
                'aktif_offset' => 125.66, 'pending_offset' => 427.26, 'nonaktif_offset' => 452.39,
                'pending_rotate' => 270, 'nonaktif_rotate' => 324,
            ];
        }

        $aktif = $this->getAnggotaAktif();
        $pending = $this->getAnggotaPending();
        $nonaktif = $this->getAnggotaNonaktif();

        $circumference = 2 * M_PI * 80;

        $aktifFraction = $aktif / $total;
        $pendingFraction = $pending / $total;

        return [
            'total' => $total,
            'aktif' => $aktif,
            'pending' => $pending,
            'nonaktif' => $nonaktif,
            'circumference' => round($circumference, 2),
            'aktif_offset' => round($circumference * (1 - $aktifFraction), 2),
            'pending_offset' => round($circumference * (1 - $pendingFraction), 2),
            'nonaktif_offset' => round($circumference * (1 - ($nonaktif / $total)), 2),
            'pending_rotate' => round(360 * $aktifFraction, 2),
            'nonaktif_rotate' => round(360 * ($aktifFraction + $pendingFraction), 2),
        ];
    }
}
