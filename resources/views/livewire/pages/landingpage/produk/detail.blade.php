<?php
use Livewire\Volt\Component;
use function Livewire\Volt\layout;
layout('components.layouts.landing');

new class extends Component {
    public $id;
    public function mount($id) { $this->id = $id; }
};
?>

<div class="max-w-3xl mx-auto px-4 py-12">
    <h1 class="text-2xl font-bold">Detail Produk</h1>
    <p class="text-slate-600">Placeholder detail produk untuk ID: {{ $id }}</p>
    <!-- TODO: pindahkan konten dari pages/landing/produk_detail.blade.php ke sini -->
    <a href="{{ route('produk') }}" class="mt-4 inline-block text-indigo-600">Kembali ke Produk</a>
</div>

