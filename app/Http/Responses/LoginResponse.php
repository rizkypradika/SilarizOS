<?php

namespace App\Http\Responses;

use Filament\Http\Responses\Auth\Contracts\LoginResponse as LoginResponseContract;
use Illuminate\Http\RedirectResponse;
use Livewire\Features\SupportRedirects\Redirector;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request): RedirectResponse|Redirector
    {
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            return redirect()->intended('/admin');
        } elseif ($user->hasRole('customer')) {
            return redirect()->intended('/customer');
        }

        return redirect()->intended('/customer');
    }
}
