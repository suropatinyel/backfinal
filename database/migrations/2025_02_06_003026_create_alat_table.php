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
        Schema::create('alat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alat_kategori_id')->references('id')->on('kategori')->nullable(false);
            $table->string('alat_nama', 150)->nullable(false);
            $table->string('alat_deskripsi', 255)->nullable(false);
            $table->integer('alat_hargaperhari')->nullable(false);         
            $table->integer('alat_stok')->nullable(false);         
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alat');
    }
};
