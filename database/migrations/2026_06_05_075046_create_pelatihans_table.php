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
    Schema::create('pelatihans', function (Blueprint $table) {
        $table->id();
        $table->string('judul');
        $table->text('deskripsi');
        $table->date('tanggal_pelaksanaan');
        $table->date('batas_pendaftaran');
        $table->string('status'); // 'Buka' atau 'Tutup'
        $table->json('biaya')->nullable(); // Menyimpan data golongan & harga dinamis
        $table->string('rekening');
        $table->json('konfigurasi_form')->nullable(); // Menyimpan checklist form
        $table->timestamps();
        $table->string('tempat'); // Kolom baru
        $table->integer('kuota');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelatihans');
    }
};
