<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pelatihans', function (Blueprint $table) {
            if (Schema::hasColumn('pelatihans', 'kuota') && Schema::hasColumn('pelatihans', 'kuota_maksimal')) {
                $table->dropColumn('kuota');
            }
            if (Schema::hasColumn('pelatihans', 'tempat') && Schema::hasColumn('pelatihans', 'lokasi')) {
                $table->dropColumn('tempat');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pelatihans', function (Blueprint $table) {
            $table->integer('kuota')->nullable();
            $table->string('tempat')->nullable();
        });
    }
};
