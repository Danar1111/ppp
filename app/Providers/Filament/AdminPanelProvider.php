<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Dashboard;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
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

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->darkMode(false)
            ->brandName('Sistem Manajemen PPP')
            ->brandLogo(fn () => view('filament.components.sidebar-brand'))
            ->brandLogoHeight('2.5rem')
            ->favicon(asset('images/logo.svg'))
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
            ->navigationGroups([
                'Manajemen SDM',
                'Struktur Organisasi',
                'Operasional & Arsip',
                'Pemenangan Pemilu',
                'Publikasi',
                'Data Wilayah',
                'Manajemen Akses',
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
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
            ->plugins([
                FilamentShieldPlugin::make()
                    ->navigationGroup('Manajemen Akses')
                    ->navigationSort(2)
                    ->navigationLabel('Peran & Hak Akses')
                    ->pluralModelLabel('Peran & Hak Akses')
                    ->modelLabel('Peran & Hak Akses'),
            ])
            ->authMiddleware([
                \App\Http\Middleware\RedirectToPortalLogin::class,
            ]);
    }
}
