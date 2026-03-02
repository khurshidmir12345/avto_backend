<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('moshina_elon_images', function (Blueprint $table) {
            $table->string('disk', 20)->default('r2')->after('path');
        });
    }

    public function down(): void
    {
        Schema::table('moshina_elon_images', function (Blueprint $table) {
            $table->dropColumn('disk');
        });
    }
};
