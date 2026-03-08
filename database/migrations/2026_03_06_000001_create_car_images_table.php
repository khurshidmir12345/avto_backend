<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('car_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('car_id')->nullable()->constrained('moshina_elons')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('image_key');
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index('car_id');
            $table->index('user_id');
        });

        // Migrate data from moshina_elon_images
        if (Schema::hasTable('moshina_elon_images')) {
            $rows = DB::table('moshina_elon_images')
                ->whereNotNull('path')
                ->orWhereNotNull('url')
                ->get();

            foreach ($rows as $row) {
                $imageKey = $row->path ?? $row->url;
                if (empty($imageKey)) {
                    continue;
                }
                DB::table('car_images')->insert([
                    'car_id' => $row->moshina_elon_id,
                    'user_id' => $row->user_id,
                    'image_key' => $imageKey,
                    'sort_order' => $row->sort_order ?? 0,
                    'created_at' => $row->created_at,
                    'updated_at' => $row->updated_at,
                ]);
            }
        }

        Schema::dropIfExists('moshina_elon_images');
    }

    public function down(): void
    {
        Schema::create('moshina_elon_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('moshina_elon_id')->nullable()->constrained('moshina_elons')->onDelete('cascade');
            $table->string('path');
            $table->string('url')->nullable();
            $table->string('disk')->default('r2');
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
            $table->index('moshina_elon_id');
            $table->index('user_id');
        });

        DB::statement("
            INSERT INTO moshina_elon_images (user_id, moshina_elon_id, path, url, disk, sort_order, created_at, updated_at)
            SELECT user_id, car_id, image_key, image_key, 'r2', sort_order, created_at, updated_at
            FROM car_images
        ");

        Schema::dropIfExists('car_images');
    }
};
