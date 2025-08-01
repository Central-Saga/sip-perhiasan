@php
$transaksis = \App\Models\Transaksi::all();
$statusList = ['PENDING', 'DIBAYAR', 'SELESAI', 'DITOLAK'];
$metodeList = ['TRANSFER' => 'Transfer Bank', 'CASH' => 'Tunai'];
@endphp
<div class="py-12 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto">
        <div class="bg-white shadow-xl rounded-2xl p-10">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Tambah Pembayaran Baru</h2>
            <form method="POST" action="{{ route('pembayaran.create') }}" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-6">
                        <div>
                            <label for="transaksi_id" class="block text-base font-medium text-gray-700 mb-1">Transaksi</label>
                            <select id="transaksi_id" name="transaksi_id" class="w-full py-3 px-4 rounded-lg text-base border border-gray-300 focus:ring-blue-500 focus:border-blue-500 bg-white">
                                <option value="">Pilih Transaksi</option>
                                @foreach($transaksis as $transaksi)
                                    <option value="{{ $transaksi->id }}" {{ old('transaksi_id') == $transaksi->id ? 'selected' : '' }}>
                                        {{ $transaksi->kode_transaksi }} - Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}
                                    </option>
                                @endforeach
                            </select>
                            @error('transaksi_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="tanggal_bayar" class="block text-base font-medium text-gray-700 mb-1">Tanggal Pembayaran</label>
                            <input id="tanggal_bayar" type="date" name="tanggal_bayar" class="w-full py-3 px-4 rounded-lg text-base border border-gray-300 focus:ring-blue-500 focus:border-blue-500 bg-white" value="{{ old('tanggal_bayar') }}">
                            @error('tanggal_bayar') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="status" class="block text-base font-medium text-gray-700 mb-1">Status</label>
                            <select id="status" name="status" class="w-full py-3 px-4 rounded-lg text-base border border-gray-300 focus:ring-blue-500 focus:border-blue-500 bg-white">
                                <option value="">Pilih Status</option>
                                @foreach($statusList as $status)
                                    <option value="{{ $status }}" {{ old('status') == $status ? 'selected' : '' }}>{{ ucfirst(strtolower($status)) }}</option>
                                @endforeach
                            </select>
                            @error('status') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="space-y-6">
                        <div>
                            <label for="metode" class="block text-base font-medium text-gray-700 mb-1">Metode Pembayaran</label>
                            <select id="metode" name="metode" class="w-full py-3 px-4 rounded-lg text-base border border-gray-300 focus:ring-blue-500 focus:border-blue-500 bg-white" onchange="toggleBuktiTransfer(this.value)">
                                <option value="">Pilih Metode</option>
                                @foreach($metodeList as $key => $label)
                                    <option value="{{ $key }}" {{ old('metode') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('metode') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div id="bukti_transfer_field" style="display: none;">
                            <label for="bukti_transfer" class="block text-base font-medium text-gray-700 mb-1">Bukti Transfer</label>
                            <input id="bukti_transfer" type="file" name="bukti_transfer" accept="image/*,.pdf" class="w-full py-3 px-4 rounded-lg text-base border border-gray-300 focus:ring-blue-500 focus:border-blue-500 bg-white">
                            @error('bukti_transfer') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            <div id="preview_bukti_transfer" class="mt-2"></div>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-end mt-10 gap-3">
                    <a href="{{ route('pembayaran.index') }}" class="px-6 py-3 text-blue-600 border border-blue-200 bg-blue-50 hover:bg-blue-100 rounded-lg font-medium shadow">Batal</a>
                    <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold shadow-md flex items-center">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
function toggleBuktiTransfer(val) {
    const field = document.getElementById('bukti_transfer_field');
    if (val === 'TRANSFER') {
        field.style.display = '';
    } else {
        field.style.display = 'none';
        document.getElementById('preview_bukti_transfer').innerHTML = '';
    }
}
document.getElementById('bukti_transfer')?.addEventListener('change', function(e) {
    const preview = document.getElementById('preview_bukti_transfer');
    preview.innerHTML = '';
    if (e.target.files && e.target.files[0]) {
        const file = e.target.files[0];
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(ev) {
                preview.innerHTML = `<img src="${ev.target.result}" class="w-32 h-32 object-cover rounded-lg shadow-sm" />`;
            };
            reader.readAsDataURL(file);
        } else if (file.type === 'application/pdf') {
            preview.innerHTML = '<span class="text-sm text-gray-600">File PDF terpilih</span>';
        }
    }
});
window.addEventListener('DOMContentLoaded', function() {
    toggleBuktiTransfer(document.getElementById('metode').value);
});
</script>