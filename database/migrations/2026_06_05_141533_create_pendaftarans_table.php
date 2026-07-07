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
    Schema::create('pendaftarans', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->foreignId('pelatihan_id')->constrained()->onDelete('cascade');
        $table->string('nama_lengkap');
        $table->string('jenis_kelamin');
        $table->integer('usia');
        $table->string('pengalaman_melatih');
        $table->string('pengalaman_lainnya')->nullable();
        $table->string('pekerjaan');
        $table->string('asal_daerah');
        $table->string('ukuran_baju');
        $table->string('golongan_biaya'); // Menyimpan nama golongan biaya yang dipilih
        $table->string('bukti_pembayaran'); // Menyimpan path file gambar
        $table->string('surat_rekomendasi')->nullable(); // Menyimpan path file PDF (opsional)
        $table->string('status')->default('Menunggu'); // Menunggu, Diterima, Ditolak
        $table->text('alasan_ditolak')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendaftarans');
    }
};
