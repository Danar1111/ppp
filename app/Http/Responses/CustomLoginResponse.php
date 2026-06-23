<?php

namespace App\Http\Responses;

use Filament\Auth\Http\Responses\Contracts\LoginResponse;
use Illuminate\Http\RedirectResponse;
use Livewire\Features\SupportRedirects\Redirector;

class CustomLoginResponse implements LoginResponse
{
    public function toResponse($request): RedirectResponse|Redirector
    {
        $user = auth()->user();

        if ($user && $user->hasAnyRole([
            'Super Admin',
            'Admin Pusat (DPP)',
            'Admin Wilayah (DPW)',
            'Admin Cabang (DPC)',
            'Admin Kecamatan (PAC)',
        ])) {
            return redirect()->intended('/admin');
        }

        return redirect()->intended('/portal');
    }
}
