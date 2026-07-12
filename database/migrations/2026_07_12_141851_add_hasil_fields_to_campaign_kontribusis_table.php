<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('campaign_kontribusis', function (Blueprint $table) {
            $table->string('file_hasil')->nullable()->after('bukti_transfer');
            $table->string('link_hasil')->nullable()->after('file_hasil');
        });
    }

    public function down()
    {
        Schema::table('campaign_kontribusis', function (Blueprint $table) {
            $table->dropColumn(['file_hasil', 'link_hasil']);
        });
    }
};
