<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('moshina_elon_images', function (Blueprint $table) {
            $table->dropForeign(['moshina_elon_id']);
        });

        Schema::table('moshina_elon_images', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained()->onDelete('cascade');
            $table->index('user_id');
        });

        DB::statement('
            UPDATE moshina_elon_images i
            INNER JOIN moshina_elons e ON i.moshina_elon_id = e.id
            SET i.user_id = e.user_id
        ');

        DB::statement('ALTER TABLE moshina_elon_images MODIFY moshina_elon_id BIGINT UNSIGNED NULL');

        Schema::table('moshina_elon_images', function (Blueprint $table) {
            $table->foreign('moshina_elon_id')->references('id')->on('moshina_elons')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('moshina_elon_images', function (Blueprint $table) {
            $table->dropForeign(['moshina_elon_id']);
        });

        DB::statement('ALTER TABLE moshina_elon_images MODIFY moshina_elon_id BIGINT UNSIGNED NOT NULL');

        Schema::table('moshina_elon_images', function (Blueprint $table) {
            $table->foreign('moshina_elon_id')->references('id')->on('moshina_elons')->onDelete('cascade');
        });

        Schema::table('moshina_elon_images', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropIndex(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
