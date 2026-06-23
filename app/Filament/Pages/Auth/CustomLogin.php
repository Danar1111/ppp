<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Actions\Action;

class CustomLogin extends BaseLogin
{
    protected string $view = 'custom-login';

    protected static string $layout = 'filament-panels::components.layout.base';

    protected function getAuthenticateFormAction(): Action
    {
        return parent::getAuthenticateFormAction()
            ->color('warning')
            ->extraAttributes([
                'class' => 'w-full py-3.5 rounded-xl shadow-lg shadow-amber-600/30 hover:shadow-xl hover:shadow-amber-600/40 hover:-translate-y-0.5 active:scale-95 transition-all duration-200 bg-[#D97706] hover:bg-[#B45309]'
            ]);
    }

    protected function getEmailFormComponent(): \Filament\Schemas\Components\Component
    {
        return parent::getEmailFormComponent()
            ->extraInputAttributes([
                'class' => 'rounded-xl border-slate-200 focus:ring-2 focus:ring-[#005B2B] focus:border-[#005B2B]'
            ]);
    }

    protected function getPasswordFormComponent(): \Filament\Schemas\Components\Component
    {
        return parent::getPasswordFormComponent()
            ->extraInputAttributes([
                'class' => 'rounded-xl border-slate-200 focus:ring-2 focus:ring-[#005B2B] focus:border-[#005B2B]'
            ]);
    }

    protected function hasFullWidthFormActions(): bool
    {
        return true;
    }
}
