<?php
use Livewire\Volt\Component;
use function Livewire\Volt\layout;
layout('components.layouts.landing');

new class extends Component {
    // Tambahkan properti / method sesuai kebutuhan nanti
};
?>

<div class="max-w-4xl mx-auto px-4 py-12">
    <h1 class="text-2xl font-bold">Keranjang</h1>
    <p class="text-slate-600">Halaman keranjang (placeholder).</p>
    <!-- TODO: pindahkan konten dari pages/landing/cart.blade.php ke sini -->
    <a href="{{ route('checkout') }}" class="mt-4 inline-block px-4 py-2 bg-indigo-600 text-white rounded">Checkout</a>
  </div>

