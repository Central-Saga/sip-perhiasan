@extends("layouts.app")

@section("title", "Custom Request Perhiasan - SIP Perhiasan")

@section("content")
<div class="max-w-7xl mx-auto mt-16 px-6 py-8">
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-slate-800 dark:text-white mb-4">Custom Request Perhiasan</h1>
        <p class="text-lg text-slate-600 dark:text-slate-300 max-w-3xl mx-auto">Buat perhiasan sesuai keinginan Anda. Deskripsikan kebutuhan Anda, pilih material, ukuran, dan unggah referensi untuk hasil terbaik.</p>
    </div>
    
    <div class="bg-white dark:bg-zinc-800 shadow-xl rounded-2xl p-8 max-w-4xl mx-auto">
        @if(session("message"))
            <div class="bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-900 text-green-700 dark:text-green-300 p-4 mb-6 rounded-lg">
                {{ session("message") }}
            </div>
        @endif
        
        <form action="{{ route("custom.submit") }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Kategori Perhiasan -->
                <div>
                    <label for="kategori" class="block text-slate-700 dark:text-slate-300 font-medium mb-2">Kategori Perhiasan</label>
                    <input type="text" name="kategori" id="kategori" placeholder="Masukkan jenis perhiasan (Cincin, Kalung, Gelang, dll)" class="w-full border border-slate-300 dark:border-slate-600 rounded-lg px-4 py-3 bg-white dark:bg-zinc-700/50 text-slate-800 dark:text-slate-200 focus:ring focus:ring-indigo-200">
                    @error("kategori") <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                
                <!-- Material -->
                <div>
                    <label for="material" class="block text-slate-700 dark:text-slate-300 font-medium mb-2">Material</label>
                    <select name="material" id="material" class="w-full border border-slate-300 dark:border-slate-600 rounded-lg px-4 py-3 bg-white dark:bg-zinc-700/50 text-slate-800 dark:text-slate-200 focus:ring focus:ring-indigo-200">
                        <option value="">Pilih Material</option>
                        <option value="Emas Kuning">Emas Kuning</option>
                        <option value="Emas Putih">Emas Putih</option>
                        <option value="Perak">Perak</option>
                        <option value="Platinum">Platinum</option>
                        <option value="Titanium">Titanium</option>
                        <option value="Stainless Steel">Stainless Steel</option>
                    </select>
                    @error("material") <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                
                <!-- Ukuran -->
                <div>
                    <label for="ukuran" class="block text-slate-700 dark:text-slate-300 font-medium mb-2">Ukuran</label>
                    <input type="text" name="ukuran" id="ukuran" placeholder="Contoh: Cincin ukuran 7, Gelang 18cm, dll" class="w-full border border-slate-300 dark:border-slate-600 rounded-lg px-4 py-3 bg-white dark:bg-zinc-700/50 text-slate-800 dark:text-slate-200 focus:ring focus:ring-indigo-200">
                    @error("ukuran") <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            
                            </div>
                
                <!-- Referensi (URL Gambar) -->
                <div>
                    <label for="gambar_referensi" class="block text-slate-700 dark:text-slate-300 font-medium mb-2">Upload Referensi</label>
                    <div class="border-2 border-dashed border-slate-300 dark:border-slate-600 rounded-lg p-6 text-center">
                        <label for="file-upload" class="cursor-pointer">
                            <div class="text-slate-500 dark:text-slate-400">
                                <i class="fa-solid fa-cloud-arrow-up text-3xl mb-2"></i>
                                <p>Klik untuk upload gambar referensi</p>
                                <p class="text-xs mt-1">(opsional, maks. 2MB)</p>
                            </div>
                            <input id="file-upload" type="file" name="gambar_referensi" class="hidden" accept="image/*">
                        </label>
                    </div>
                    @error("gambar_referensi") <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>
            
            <!-- Deskripsi Kebutuhan (Full Width) -->
            <div>
                <label for="deskripsi" class="block text-slate-700 dark:text-slate-300 font-medium mb-2">Deskripsi Kebutuhan</label>
                <textarea name="deskripsi" id="deskripsi" rows="5" placeholder="Jelaskan perhiasan yang Anda inginkan..." class="w-full border border-slate-300 dark:border-slate-600 rounded-lg px-4 py-3 bg-white dark:bg-zinc-700/50 text-slate-800 dark:text-slate-200 focus:ring focus:ring-indigo-200"></textarea>
                @error("deskripsi") <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            
            <!-- Submit Button -->
            <div class="pt-4">
                <button type="submit" class="w-full bg-indigo-500 hover:bg-indigo-600 text-white font-bold py-3 px-6 rounded-lg transition flex items-center justify-center gap-2">
                    <i class="fa-solid fa-paper-plane"></i>
                    Kirim Custom Request
                </button>
            </div>
        </form>
    </div>
    
    <div class="mt-16 max-w-4xl mx-auto">
        <h2 class="text-2xl font-bold text-slate-800 dark:text-white mb-6">Bagaimana Proses Custom Request Bekerja?</h2>
        
        <div class="grid md:grid-cols-3 gap-8">
            <div class="bg-white dark:bg-zinc-800 p-6 rounded-xl shadow-md">
                <div class="bg-indigo-100 dark:bg-indigo-900/30 w-14 h-14 rounded-full flex items-center justify-center mb-4">
                    <i class="fa-solid fa-pencil text-indigo-500 text-xl"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-2">1. Kirim Request</h3>
                <p class="text-slate-600 dark:text-slate-300">Isi form dengan detail permintaan Anda. Semakin spesifik, semakin baik hasilnya.</p>
            </div>
            
            <div class="bg-white dark:bg-zinc-800 p-6 rounded-xl shadow-md">
                <div class="bg-indigo-100 dark:bg-indigo-900/30 w-14 h-14 rounded-full flex items-center justify-center mb-4">
                    <i class="fa-solid fa-comments text-indigo-500 text-xl"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-2">2. Konsultasi</h3>
                <p class="text-slate-600 dark:text-slate-300">Tim kami akan menghubungi untuk membahas detail, estimasi harga dan waktu pengerjaan.</p>
            </div>
            
            <div class="bg-white dark:bg-zinc-800 p-6 rounded-xl shadow-md">
                <div class="bg-indigo-100 dark:bg-indigo-900/30 w-14 h-14 rounded-full flex items-center justify-center mb-4">
                    <i class="fa-solid fa-gem text-indigo-500 text-xl"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-2">3. Pembuatan</h3>
                <p class="text-slate-600 dark:text-slate-300">Setelah konfirmasi, perhiasan Anda akan dibuat oleh pengrajin berpengalaman kami.</p>
            </div>
        </div>
    </div>
</div>
@endsection
