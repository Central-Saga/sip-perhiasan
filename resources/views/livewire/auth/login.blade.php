<?php

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
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

        $user = Auth::user();
        $isPelanggan = $user && $user->hasRole('Pelanggan');
        $defaultRoute = $isPelanggan
            ? route('home', absolute: false)
            : route('dashboard', absolute: false);

        // Untuk Pelanggan, lakukan full reload (navigate: false) agar header/landing tersinkron.
        $this->redirectIntended(default: $defaultRoute, navigate: ! $isPelanggan);
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
}; ?>

<div class="flex flex-col gap-6">
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-slate-800 dark:text-white flex items-center justify-center gap-2">
            <i class="fa-solid fa-gem text-indigo-500"></i> {{ __('Selamat Datang Kembali') }}
        </h2>
        <p class="text-slate-500 dark:text-slate-400 text-sm">{{ __('Silahkan masuk ke akun Anda untuk mengelola data perhiasan') }}</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form wire:submit="login" class="flex flex-col gap-5">
        <!-- Email Address -->
        <div class="space-y-1.5">
            <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('Email') }}</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-500 dark:text-slate-400">
                    <i class="fa-regular fa-envelope"></i>
                </span>
                <input
                    wire:model="email"
                    type="email"
                    id="email"
                    required
                    autofocus
                    autocomplete="email"
                    placeholder="email@example.com"
                    class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-slate-300 dark:border-slate-600 focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent dark:bg-slate-700 dark:text-white text-sm"
                >
            </div>
            @error('email') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
        </div>

        <!-- Password -->
        <div class="space-y-1.5">
            <div class="flex justify-between">
                <label for="password" class="block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('Password') }}</label>
                @if (Route::has('password.request'))
                    <a class="text-sm text-indigo-600 hover:text-indigo-500 dark:text-indigo-400" href="{{ route('password.request') }}" wire:navigate>
                        {{ __('Lupa password?') }}
                    </a>
                @endif
            </div>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-500 dark:text-slate-400">
                    <i class="fa-solid fa-lock"></i>
                </span>
                <input
                    wire:model="password"
                    type="password"
                    id="password"
                    required
                    autocomplete="current-password"
                    placeholder="••••••••"
                    class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-slate-300 dark:border-slate-600 focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent dark:bg-slate-700 dark:text-white text-sm"
                >
                <button type="button" onclick="togglePasswordVisibility('password')" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-500 dark:text-slate-400">
                    <i class="fa-regular fa-eye" id="password-toggle-icon"></i>
                </button>
            </div>
            @error('password') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
        </div>

        <!-- Remember Me -->
        <div class="flex items-center">
            <input wire:model="remember" id="remember" type="checkbox" class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-700">
            <label for="remember" class="ml-2 block text-sm text-slate-600 dark:text-slate-400">{{ __('Ingat saya') }}</label>
        </div>

        <div class="mt-2">
            <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white font-medium rounded-lg shadow-md transition-colors">
                <i class="fa-solid fa-right-to-bracket"></i> {{ __('Masuk') }}
            </button>
        </div>
    </form>

    @if (Route::has('register'))
        <div class="text-center mt-4 text-sm text-slate-600 dark:text-slate-400">
            {{ __('Belum memiliki akun?') }}
            <a href="{{ route('register') }}" class="font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400" wire:navigate>{{ __('Daftar sekarang') }}</a>
        </div>
    @endif
    
    <script>
        function togglePasswordVisibility(inputId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(inputId + '-toggle-icon');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</div>
