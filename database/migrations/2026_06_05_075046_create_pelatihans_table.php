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
        $table->string('status');
        $table->json('biaya')->nullable();
        $table->string('rekening');
        $table->json('konfigurasi_form')->nullable();
        $table->string('lokasi');           // sudah diganti dari 'tempat'
        $table->integer('kuota_maksimal');  // sudah diganti dari 'kuota'
        $table->timestamps();
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
