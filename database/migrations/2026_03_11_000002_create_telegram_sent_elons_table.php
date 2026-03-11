<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('telegram_sent_elons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('moshina_elon_id')->constrained('moshina_elons')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('telegram_bot_id')->constrained('telegram_bots')->cascadeOnDelete();
            $table->string('channel_id');
            $table->bigInteger('message_id');
            $table->timestamps();

            $table->index(['moshina_elon_id', 'channel_id']);
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('telegram_sent_elons');
    }
};
