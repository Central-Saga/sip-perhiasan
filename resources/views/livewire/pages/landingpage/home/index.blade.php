<?php

use function Livewire\Volt\{state, mount, layout};

layout('components.layouts.landing');

state(['title' => 'Landing Home', 'description' => 'Silakan isi konten landing di sini.']);

?>

<div class="max-w-7xl mx-auto px-4 py-24">
    <h1 class="text-3xl font-bold text-slate-800">{{ $title }}</h1>
    <p class="text-slate-600">{{ $description }}</p>
</div>