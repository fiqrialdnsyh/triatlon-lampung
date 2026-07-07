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
        Schema::create('personils', function (Blueprint $table) {
            $table->id();
            // Kategori Penentu
            $table->enum('kategori', ['Atlet', 'Pelatih', 'Wasit']);

            // Data Umum (Berlaku untuk ketiganya)
            $table->string('nama');
            $table->string('asal_daerah'); // Kab/Kota
            $table->string('foto')->nullable(); // File JPG/PNG

            // Data Khusus Pelatih & Wasit
            $table->string('tingkat_lisensi')->nullable();
            $table->string('sertifikat_lisensi')->nullable(); // File PDF

            // Data Khusus Atlet
            $table->string('ttl')->nullable(); // Tempat, Tanggal Lahir
            $table->integer('umur')->nullable();
            $table->enum('jenis_identitas', ['NIK', 'NIM', 'NISN'])->nullable();
            $table->string('nomor_identitas')->nullable();
            $table->text('riwayat_prestasi')->nullable();
            $table->string('kontak')->nullable(); // Nomor WhatsApp

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personils');
    }
};
