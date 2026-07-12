<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            // Menambahkan ID relasi ke tabel lama
            $table->foreignId('main_event_id')->nullable()->after('id')->constrained('main_events')->onDelete('cascade');
        });
    }
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropForeign(['main_event_id']);
            $table->dropColumn('main_event_id');
        });
    }
};
