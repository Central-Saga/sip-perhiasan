<?php
use function Livewire\Volt\layout;
layout('components.layouts.landing');
?>

<div class="max-w-3xl mx-auto px-4 py-12">
  <h1 class="text-2xl md:text-3xl font-extrabold text-slate-800 dark:text-slate-100 mb-6 flex items-center gap-2">
    <i class="fa-solid fa-wand-magic-sparkles text-indigo-500"></i> Detail Custom Request
  </h1>

  <div class="bg-white dark:bg-slate-800 rounded-xl shadow p-6">
    <div class="flex items-start gap-4">
      <div id="crImage" class="w-28 h-28 rounded-lg overflow-hidden border border-slate-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-700 flex items-center justify-center text-slate-400">No Image</div>
      <div class="flex-1">
        <div class="flex items-center justify-between">
          <h2 class="text-lg font-semibold text-slate-800 dark:text-slate-100">Ringkasan</h2>
          <span id="crStatus" class="text-xs px-2 py-1 rounded-full bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-200">pending</span>
        </div>
        <dl class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-2 text-sm">
          <div><dt class="text-slate-500">Kategori</dt><dd id="crKategori" class="font-medium text-slate-800 dark:text-slate-100">-</dd></div>
          <div><dt class="text-slate-500">Material</dt><dd id="crMaterial" class="font-medium text-slate-800 dark:text-slate-100">-</dd></div>
          <div><dt class="text-slate-500">Ukuran</dt><dd id="crUkuran" class="font-medium text-slate-800 dark:text-slate-100">-</dd></div>
          <div><dt class="text-slate-500">Berat</dt><dd id="crBerat" class="font-medium text-slate-800 dark:text-slate-100">0 gram</dd></div>
        </dl>
      </div>
    </div>
    <div class="mt-4">
      <h3 class="text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1">Deskripsi</h3>
      <p id="crDeskripsi" class="text-slate-700 dark:text-slate-300 text-sm"></p>
    </div>
  </div>

  <div class="mt-6 flex items-center gap-3">
    <a href="{{ route('cart') }}" class="px-4 py-2 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 font-medium flex items-center gap-2 text-sm"><i class="fa-solid fa-arrow-left"></i> Kembali ke Keranjang</a>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function(){
    let cr = null;
    try { cr = JSON.parse(localStorage.getItem('customRequest') || 'null'); } catch(_) {}
    if (!cr) return;
    const img = document.getElementById('crImage');
    if (cr.gambar_referensi) {
      img.innerHTML = `<img src="${cr.gambar_referensi}" class="w-full h-full object-cover"/>`;
    }
    document.getElementById('crStatus').innerText = cr.status || 'pending';
    document.getElementById('crKategori').innerText = cr.kategori || '-';
    document.getElementById('crMaterial').innerText = cr.material || '-';
    document.getElementById('crUkuran').innerText = cr.ukuran || '-';
    document.getElementById('crBerat').innerText = (cr.berat || 0) + ' gram';
    document.getElementById('crDeskripsi').innerText = cr.deskripsi || '';
  });
</script>

