<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_views', function (Blueprint $table) {
            $table->id();
            $table->string('page', 50)->index();
            $table->string('device_id', 64)->nullable()->index();
            $table->string('platform', 20)->nullable();
            $table->date('view_date')->index();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['page', 'view_date']);
            $table->index(['view_date', 'device_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_views');
    }
};
