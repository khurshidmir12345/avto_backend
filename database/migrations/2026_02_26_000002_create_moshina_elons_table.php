<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('moshina_elons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('marka');
            $table->string('model')->nullable();
            $table->unsignedSmallInteger('yil');
            $table->unsignedInteger('probeg');
            $table->decimal('narx', 15, 2);
            $table->enum('valyuta', ['USD', 'UZS'])->default('USD');
            $table->string('rang')->nullable();
            $table->enum('yoqilgi_turi', ['benzin', 'metan', 'benzin+metan', 'dizel', 'elektr', 'gibrid']);
            $table->enum('uzatish_qutisi', ['mexanika', 'avtomat'])->default('mexanika');
            $table->string('kraska_holati')->nullable();
            $table->string('shahar');
            $table->string('telefon');
            $table->text('tavsif')->nullable();
            $table->enum('holati', ['active', 'sold', 'inactive'])->default('active');
            $table->boolean('bank_kredit')->default(false);
            $table->boolean('general')->default(false);
            $table->timestamps();

            $table->index('user_id');
            $table->index('marka');
            $table->index('shahar');
            $table->index('holati');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('moshina_elons');
    }
};
