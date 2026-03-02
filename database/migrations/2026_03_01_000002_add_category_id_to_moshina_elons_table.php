<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('moshina_elons', function (Blueprint $table) {
            $table->foreignId('category_id')->after('user_id')->nullable()->constrained()->nullOnDelete();
            $table->index('category_id');
        });
    }

    public function down(): void
    {
        Schema::table('moshina_elons', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
        });
    }
};
