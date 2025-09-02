<?php

use App\Models\User;
use App\Models\Pelanggan;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $no_telepon = '';
    public string $alamat = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
            'no_telepon' => ['required', 'string', 'max:255'],
            'alamat' => ['required', 'string'],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $userData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
        ];
        event(new Registered(($user = User::create($userData))));

        if (method_exists($user, 'assignRole')) {
            $user->assignRole('Pelanggan');
        }
        Pelanggan::create([
            'user_id' => $user->id,
            'no_telepon' => $validated['no_telepon'],
            'alamat' => $validated['alamat'],
            'status' => true,
        ]);

        Auth::login($user);

        $this->redirectIntended(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="flex flex-col gap-6">
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-slate-800 dark:text-white flex items-center justify-center gap-2">
            <i class="fa-solid fa-user-plus text-indigo-500"></i> {{ __('Buat Akun Baru') }}
        </h2>
        <p class="text-slate-500 dark:text-slate-400 text-sm">{{ __('Lengkapi data diri Anda untuk membuat akun') }}</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form wire:submit="register" class="flex flex-col gap-4">
        <!-- Name -->
        <div class="space-y-1.5">
            <label for="name" class="block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('Nama Lengkap') }}</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-500 dark:text-slate-400">
                    <i class="fa-regular fa-user"></i>
                </span>
                <input
                    wire:model="name"
                    type="text"
                    id="name"
                    required
                    autofocus
                    autocomplete="name"
                    placeholder="Nama lengkap Anda"
                    class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-slate-300 dark:border-slate-600 focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent dark:bg-slate-700 dark:text-white text-sm"
                >
            </div>
            @error('name') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
        </div>

        <!-- Phone Number -->
        <div class="space-y-1.5">
            <label for="no_telepon" class="block text-sm font-medium text-slate-700 dark:text-slate-300">No. Telepon</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-500 dark:text-slate-400">
                    <i class="fa-solid fa-phone"></i>
                </span>
                <input
                    wire:model="no_telepon"
                    type="text"
                    id="no_telepon"
                    required
                    placeholder="08xxxxxxxxxx"
                    class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-slate-300 dark:border-slate-600 focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent dark:bg-slate-700 dark:text-white text-sm"
                >
            </div>
            @error('no_telepon') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
        </div>

        <!-- Address -->
        <div class="space-y-1.5">
            <label for="alamat" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Alamat</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-start pl-3 pt-2 text-slate-500 dark:text-slate-400">
                    <i class="fa-solid fa-map-location-dot"></i>
                </span>
                <textarea
                    wire:model="alamat"
                    id="alamat"
                    required
                    rows="3"
                    placeholder="Alamat lengkap Anda"
                    class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-slate-300 dark:border-slate-600 focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent dark:bg-slate-700 dark:text-white text-sm"
                ></textarea>
            </div>
            @error('alamat') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
        </div>

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
                    autocomplete="email"
                    placeholder="email@example.com"
                    class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-slate-300 dark:border-slate-600 focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent dark:bg-slate-700 dark:text-white text-sm"
                >
            </div>
            @error('email') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
        </div>

        <!-- Password -->
        <div class="space-y-1.5">
            <label for="password" class="block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('Password') }}</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-500 dark:text-slate-400">
                    <i class="fa-solid fa-lock"></i>
                </span>
                <input
                    wire:model="password"
                    type="password"
                    id="password"
                    required
                    autocomplete="new-password"
                    placeholder="••••••••"
                    class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-slate-300 dark:border-slate-600 focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent dark:bg-slate-700 dark:text-white text-sm"
                >
                <button type="button" onclick="togglePasswordVisibility('password')" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-500 dark:text-slate-400">
                    <i class="fa-regular fa-eye" id="password-toggle-icon"></i>
                </button>
            </div>
            @error('password') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
        </div>

        <!-- Confirm Password -->
        <div class="space-y-1.5">
            <label for="password_confirmation" class="block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('Konfirmasi Password') }}</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-500 dark:text-slate-400">
                    <i class="fa-solid fa-shield-halved"></i>
                </span>
                <input
                    wire:model="password_confirmation"
                    type="password"
                    id="password_confirmation"
                    required
                    autocomplete="new-password"
                    placeholder="••••••••"
                    class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-slate-300 dark:border-slate-600 focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent dark:bg-slate-700 dark:text-white text-sm"
                >
                <button type="button" onclick="togglePasswordVisibility('password_confirmation')" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-500 dark:text-slate-400">
                    <i class="fa-regular fa-eye" id="password_confirmation-toggle-icon"></i>
                </button>
            </div>
            @error('password_confirmation') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
        </div>

        <div class="mt-2">
            <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white font-medium rounded-lg shadow-md transition-colors">
                <i class="fa-solid fa-user-plus"></i> {{ __('Daftar Sekarang') }}
            </button>
        </div>
    </form>

    <div class="text-center mt-4 text-sm text-slate-600 dark:text-slate-400">
        {{ __('Sudah memiliki akun?') }}
        <a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400" wire:navigate>{{ __('Masuk') }}</a>
    </div>
    
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
