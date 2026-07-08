<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // Data Lengkap Peserta Sesuai Revisi Baru
            $table->string('nama_lengkap');
            $table->string('email');
            $table->string('nomor_telepon');
            $table->integer('usia');
            $table->string('jenis_kelamin');
            $table->string('asal_daerah'); // Menggantikan asal_klub_daerah
            $table->string('kategori_lomba');

            // Data Finansial & Validasi QR
            $table->string('golongan_biaya');
            $table->integer('nominal_bayar');
            $table->string('bukti_transfer');
            $table->string('qr_token')->unique()->nullable();

            // Status & Catatan Admin
            $table->enum('status_pembayaran', ['Menunggu', 'Valid', 'Ditolak'])->default('Menunggu');
            $table->text('pesan_penolakan')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_registrations');
    }
};
