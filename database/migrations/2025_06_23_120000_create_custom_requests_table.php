<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('custom_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('deskripsi');
            $table->decimal('estimasi_harga', 12, 2);
            $table->string('kategori');
            $table->string('lokasi');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('custom_requests');
    }
};
