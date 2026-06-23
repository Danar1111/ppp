<?php

namespace App\Filament\Member\Pages;

use App\Models\Event;
use App\Models\User as Member;
use BackedEnum;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Page
{
    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-home';

    protected string $view = 'filament.member.pages.dashboard';

    protected static ?int $navigationSort = -2;

    protected static ?string $title = 'Dashboard';

    public function getTitle(): string|Htmlable
    {
        return 'Dashboard';
    }

    public function getMember(): Member
    {
        return Auth::user();
    }

    public function getUpcomingEvents(): Collection
    {
        return Event::where('start_datetime', '>=', now())
            ->whereIn('status', ['Akan Datang', 'Sedang Berjalan'])
            ->orderBy('start_datetime', 'asc')
            ->limit(3)
            ->get();
    }

}
