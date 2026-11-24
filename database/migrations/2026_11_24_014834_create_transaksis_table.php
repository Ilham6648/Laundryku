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
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transaksi')->unique();
            $table->string('nama_pelanggan');
            $table->string('no_hp');
            $table->foreignId('layanan_id')->constrained('layanans')->restrictOnDelete();
            $table->decimal('berat_kg', 8, 2);
            $table->unsignedInteger('total_harga');
            $table->string('status_bayar')->index();
            $table->string('status_pengerjaan')->default('Proses')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
