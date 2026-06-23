<?php

namespace App\Http\Middleware;

use Filament\Http\Middleware\Authenticate as BaseAuthenticate;
use Illuminate\Http\Request;

class RedirectToPortalLogin extends BaseAuthenticate
{
    protected function redirectTo($request): ?string
    {
        return '/portal/login';
    }
}
