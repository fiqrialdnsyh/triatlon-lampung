<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Pengecekan agar tidak error jika kolom sudah terlanjur ada dari migrasi sebelumnya
        if (!Schema::hasColumn('pelatihans', 'link_wa_grup')) {
            Schema::table('pelatihans', function (Blueprint $table) {
                $table->string('link_wa_grup')->nullable()->after('status');
            });
        }

        if (!Schema::hasColumn('pendaftarans', 'pesan_penolakan')) {
            Schema::table('pendaftarans', function (Blueprint $table) {
                $table->text('pesan_penolakan')->nullable()->after('status');
                $table->timestamp('waktu_checkin')->nullable()->after('pesan_penolakan');
                $table->string('qr_token')->nullable()->after('waktu_checkin');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('pelatihans', 'link_wa_grup')) {
            Schema::table('pelatihans', function (Blueprint $table) {
                $table->dropColumn('link_wa_grup');
            });
        }

        if (Schema::hasColumn('pendaftarans', 'pesan_penolakan')) {
            Schema::table('pendaftarans', function (Blueprint $table) {
                $table->dropColumn(['pesan_penolakan', 'waktu_checkin', 'qr_token']);
            });
        }
    }
};
