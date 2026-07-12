<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('slug')->unique();
            $table->enum('tipe', ['Donasi', 'Kerjasama', 'Campaign']);
            $table->text('deskripsi');
            $table->string('poster')->nullable();
            $table->unsignedBigInteger('target_dana')->nullable(); // khusus Donasi
            $table->string('rekening')->nullable(); // khusus Donasi
            $table->string('link_wa')->nullable(); // khusus Kerjasama (CTA kontak)
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai')->nullable();
            $table->enum('status', ['Aktif', 'Selesai'])->default('Aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};
