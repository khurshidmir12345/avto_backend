<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('moshina_elon_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('moshina_elon_id')->constrained()->onDelete('cascade');
            $table->string('path');
            $table->string('url')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index('moshina_elon_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('moshina_elon_images');
    }
};
