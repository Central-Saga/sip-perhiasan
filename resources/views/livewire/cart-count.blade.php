<?php
use function Livewire\Volt\{ state, mount, action };
use App\Models\Keranjang;
use App\Models\Pelanggan;
use Illuminate\Support\Facades\Auth;

state([
    'cartCount' => 0,
]);

mount(function () {
    if (Auth::check()) {
        $pelanggan = Auth::user()->pelanggan;
        if ($pelanggan) {
            $this->cartCount = Keranjang::where('pelanggan_id', $pelanggan->id)->sum('jumlah');
        }
    }
});

$refreshCartCount = action(function() {
    if (Auth::check()) {
        $pelanggan = Auth::user()->pelanggan;
        if ($pelanggan) {
            $this->cartCount = Keranjang::where('pelanggan_id', $pelanggan->id)->sum('jumlah');
        }
    }
});
?>

@auth
<div class="relative">
    <a href="{{ route('cart') }}" id="cartBtn"
        class="relative p-2 rounded-lg border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700/20 transition-all duration-300">
        <i class="fa-solid fa-cart-shopping text-sm"></i>
        <span id="cartCount"
            class="absolute -top-1 -right-1 bg-gradient-to-r from-purple-500 to-indigo-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center font-bold">
            {{ $cartCount }}
        </span>
    </a>
</div>
@endauth

{{-- Event listener akan ditangani oleh Livewire secara otomatis --}}