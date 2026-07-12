<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('venues', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('slug')->unique();
            $table->string('daerah'); // Kolom kunci untuk fitur filter
            $table->text('alamat');
            $table->text('deskripsi')->nullable();
            $table->json('fasilitas')->nullable(); // Menyimpan banyak fasilitas sekaligus
            $table->text('rute_renang')->nullable();
            $table->text('rute_sepeda')->nullable();
            $table->text('rute_lari')->nullable();
            $table->text('area_transisi')->nullable();
            $table->string('link_maps')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('venues');
    }
};
