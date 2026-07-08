<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('slug')->unique();
            $table->text('deskripsi');
            $table->date('tanggal_pelaksanaan');
            $table->string('lokasi');
            $table->string('poster')->nullable();
            $table->enum('tipe', ['Kejurnas', 'Open']);
            $table->enum('status', ['Buka', 'Tutup', 'Selesai'])->default('Buka');

            // Kolom Baru yang wajib ada
            $table->integer('kuota_maksimal')->default(100);
            $table->dateTime('batas_pendaftaran');
            $table->string('thb_file')->nullable();
            $table->text('skema_biaya');
            $table->string('nama_bank');
            $table->string('nomor_rekening');
            $table->string('atas_nama');
            $table->string('link_wa_grup');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
