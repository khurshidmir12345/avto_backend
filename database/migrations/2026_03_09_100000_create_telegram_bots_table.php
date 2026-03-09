<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('telegram_bots', function (Blueprint $table) {
            $table->id();
            $table->string('bot_name');
            $table->string('bot_type');
            $table->string('token');
            $table->timestamps();

            $table->index('bot_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('telegram_bots');
    }
};
