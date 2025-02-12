<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('penyewaan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penyewaan_pelanggan_id')->references('id')->on('pelanggan');
            $table->date('penyewaan_tglsewa')->nullable(false);
            $table->date('penyewaan_tglkembali')->nullable(false);
            $table->enum('penyewaan_sttspembayaran', ['Lunas','Belum dibayar','DP'])->default('Belum dibayar')->nullable(false);
            $table->enum('penyewaan_sttskembali', ['Sudah kembali', 'Belum kembali'])->default('Belum kembali')->nullable(false);
            $table->integer('penyewaan_totalharga')->nullable(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penyewaans');
    }
};
