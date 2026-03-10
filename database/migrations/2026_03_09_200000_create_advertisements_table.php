<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('advertisements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title', 150);
            $table->text('description')->nullable();
            $table->string('image_key')->nullable();
            $table->string('link', 500)->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'expired', 'draft'])->default('pending');
            $table->unsignedSmallInteger('days')->default(1);
            $table->unsignedBigInteger('daily_price');
            $table->unsignedBigInteger('total_price');
            $table->unsignedBigInteger('views')->default(0);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['status', 'expires_at']);
            $table->index('user_id');
        });

        DB::table('elon_prices')->updateOrInsert(
            ['key' => 'reklama_create'],
            ['amount' => 400_000, 'created_at' => now(), 'updated_at' => now()]
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('advertisements');
        DB::table('elon_prices')->where('key', 'reklama_create')->delete();
    }
};
