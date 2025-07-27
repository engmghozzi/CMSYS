<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('components.layouts.auth')]
class Login extends Component
{
    #[Validate('required|string|email')]
    public string $email = '';

    #[Validate('required|string')]
    public string $password = '';

    public bool $remember = false;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->ensureIsNotRateLimited();

        if (! Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
        Session::regenerate();

        // Redirect to first accessible page for the user
        $redirectRoute = $this->getFirstAccessibleRoute();
        $this->redirectIntended(default: route($redirectRoute, absolute: false), navigate: true);
    }

    /**
     * Ensure the authentication request is not rate limited.
     */
    protected function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => __('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the authentication rate limiting throttle key.
     */
    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email).'|'.request()->ip());
    }

    /**
     * Get the first accessible route for the authenticated user
     */
    private function getFirstAccessibleRoute(): string
    {
        /** @var User $user */
        $user = Auth::user();
        
        // Check permissions in order of preference
        if ($user->hasPermission('dashboard.read')) {
            return 'dashboard';
        }
        
        if ($user->hasPermission('clients.read')) {
            return 'clients.index';
        }
        
        if ($user->hasPermission('contracts.read')) {
            return 'contracts.globalindex';
        }
        
        if ($user->hasPermission('payments.read')) {
            return 'payments.globalindex';
        }
        
        if ($user->hasPermission('users.read')) {
            return 'users.index';
        }
        
        if ($user->hasPermission('roles.manage')) {
            return 'roles.index';
        }
        
        if ($user->hasPermission('features.manage')) {
            return 'features.index';
        }
        
        if ($user->hasPermission('logs.read')) {
            return 'logs.index';
        }
        
        // Fallback to home if no permissions
        return 'home';
    }
}
