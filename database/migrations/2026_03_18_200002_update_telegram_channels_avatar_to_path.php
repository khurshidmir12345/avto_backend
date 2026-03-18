<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('telegram_channels', function (Blueprint $table) {
            $table->renameColumn('avatar_url', 'avatar_path');
        });

        Schema::table('telegram_channels', function (Blueprint $table) {
            $table->string('avatar_disk')->nullable()->after('avatar_path');
        });
    }

    public function down(): void
    {
        Schema::table('telegram_channels', function (Blueprint $table) {
            $table->dropColumn('avatar_disk');
        });

        Schema::table('telegram_channels', function (Blueprint $table) {
            $table->renameColumn('avatar_path', 'avatar_url');
        });
    }
};
