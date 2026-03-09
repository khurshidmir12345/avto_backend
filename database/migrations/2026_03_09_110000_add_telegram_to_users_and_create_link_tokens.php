<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->bigInteger('telegram_user_id')->nullable()->unique()->after('is_admin');
            $table->string('telegram_username')->nullable()->after('telegram_user_id');
            $table->string('telegram_first_name')->nullable()->after('telegram_username');
            $table->string('telegram_last_name')->nullable()->after('telegram_first_name');
        });

        Schema::create('telegram_link_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('token', 64)->unique();
            $table->bigInteger('telegram_user_id');
            $table->string('telegram_username')->nullable();
            $table->string('telegram_first_name')->nullable();
            $table->string('telegram_last_name')->nullable();
            $table->timestamp('expires_at');
            $table->timestamps();

            $table->index(['token', 'expires_at']);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'telegram_user_id',
                'telegram_username',
                'telegram_first_name',
                'telegram_last_name',
            ]);
        });

        Schema::dropIfExists('telegram_link_tokens');
    }
};
