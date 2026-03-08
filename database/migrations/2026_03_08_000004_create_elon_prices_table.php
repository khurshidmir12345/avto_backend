<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('elon_prices', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique()->comment('Masalan: elon_create');
            $table->unsignedBigInteger('amount')->comment('Narx UZS da');
            $table->timestamps();
        });

        \DB::table('elon_prices')->insert([
            'key' => 'elon_create',
            'amount' => 25_000,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('elon_prices');
    }
};
