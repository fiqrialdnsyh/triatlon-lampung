<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('campaign_kontribusis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('nama_lengkap');
            $table->string('instansi')->nullable(); // dipakai untuk Kerjasama
            $table->unsignedBigInteger('nominal')->nullable(); // dipakai untuk Donasi
            $table->string('bukti_transfer')->nullable(); // dipakai untuk Donasi
            $table->text('pesan')->nullable();
            $table->boolean('tampilkan_publik')->default(false); // opt-in wall of supporters
            $table->enum('status', ['Menunggu', 'Diterima', 'Ditolak'])->default('Menunggu');
            $table->text('alasan_ditolak')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaign_kontribusis');
    }
};
