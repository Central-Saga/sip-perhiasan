<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('custom_request', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelanggan_id')->constrained('pelanggan')->onDelete('cascade');
            $table->text('deskripsi');
            $table->decimal('estimasi_harga', 12, 2);
            $table->string('kategori');
            $table->string('material')->nullable();
            $table->string('ukuran')->nullable();
            $table->string('gambar_referensi')->nullable();
            $table->decimal('berat', 8, 2);
            $table->foreignId('detail_transaksi_id')->nullable()->constrained('detail_transaksi')->onDelete('cascade');
            $table->enum('status', [
                'pending',
                'reviewed',
                'price_proposed',
                'approved',
                'rejected',
                'in_progress',
                'completed',
                'cancelled'
            ])->default('pending');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('custom_request');
    }
};

