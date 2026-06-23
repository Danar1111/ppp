<?php

namespace App\Providers\Filament;

use App\Filament\Member\Pages\Dashboard;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class MemberPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('member')
            ->path('portal')
            ->authGuard('web')
            ->brandName('Portal Anggota PPP')
            ->brandLogo(fn () => view('filament.components.sidebar-brand'))
            ->brandLogoHeight('2.5rem')
            ->login(\App\Filament\Pages\Auth\CustomLogin::class)
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->darkMode(false)
            ->colors([
                // Forest Green primary brand color (#005B2B)
                'primary' => Color::hex('#005B2B'),
                // Golden Yellow accent (#D97706)
                'warning' => Color::hex('#D97706'),
                'success' => Color::hex('#166534'),
                'danger'  => Color::hex('#ba1a1a'),
                'info'    => Color::hex('#0060a8'),
            ])
            ->font('Plus Jakarta Sans')
            ->sidebarCollapsibleOnDesktop()
            ->discoverResources(in: app_path('Filament/Member/Resources'), for: 'App\Filament\Member\Resources')
            ->discoverPages(in: app_path('Filament/Member/Pages'), for: 'App\Filament\Member\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Member/Widgets'), for: 'App\Filament\Member\Widgets')
            ->widgets([])
            ->renderHook(
                PanelsRenderHook::SIDEBAR_FOOTER,
                fn (): string => view('filament.components.sidebar-footer')->render(),
            )
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
