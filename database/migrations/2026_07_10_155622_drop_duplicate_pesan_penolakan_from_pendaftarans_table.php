<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('pendaftarans', 'pesan_penolakan')) {
            Schema::table('pendaftarans', function (Blueprint $table) {
                $table->dropColumn('pesan_penolakan');
            });
        }
    }

    public function down(): void
    {
        Schema::table('pendaftarans', function (Blueprint $table) {
            $table->text('pesan_penolakan')->nullable();
        });
    }
};
