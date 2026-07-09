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

            // Biodata Lengkap
            $table->string('nama_lengkap');
            $table->string('nomor_ktp');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->integer('usia'); // Akan diisi otomatis oleh sistem
            $table->string('jenis_kelamin');
            $table->string('golongan_darah');
            $table->string('alamat');
            $table->string('asal_daerah'); // Sekarang jadi input manual bebas
            $table->string('email');
            $table->string('nomor_telepon');

            // Data Perlombaan
            $table->string('bib_name');
            $table->string('kategori_lomba');

            // Data Finansial & Validasi QR
            $table->string('golongan_biaya');
            $table->integer('nominal_bayar');
            $table->string('bukti_transfer');
            $table->string('qr_token')->unique()->nullable();

            // Status
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
