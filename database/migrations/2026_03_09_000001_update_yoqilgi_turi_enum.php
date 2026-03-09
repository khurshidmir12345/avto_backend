<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const NEW_VALUES = ['benzin', 'benzin+metan', 'benzin+propan', 'dizel', 'salarka', 'eloktor', 'gibrid'];

    private const OLD_VALUES = ['benzin', 'metan', 'benzin+metan', 'dizel', 'elektr', 'gibrid'];

    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            // 1. Kengaytirish: eski va yangi qiymatlarni qo'shish
            $allValues = array_unique(array_merge(self::OLD_VALUES, self::NEW_VALUES));
            $enumValues = "'" . implode("','", $allValues) . "'";
            DB::statement("ALTER TABLE moshina_elons MODIFY COLUMN yoqilgi_turi ENUM({$enumValues}) NOT NULL");

            // 2. Ma'lumotlarni yangilash
            DB::table('moshina_elons')
                ->where('yoqilgi_turi', 'metan')
                ->update(['yoqilgi_turi' => 'benzin+metan']);

            DB::table('moshina_elons')
                ->where('yoqilgi_turi', 'elektr')
                ->update(['yoqilgi_turi' => 'eloktor']);

            // 3. Enum ni yakuniy qiymatlarga qisqartirish
            $enumValues = "'" . implode("','", self::NEW_VALUES) . "'";
            DB::statement("ALTER TABLE moshina_elons MODIFY COLUMN yoqilgi_turi ENUM({$enumValues}) NOT NULL");
        } else {
            DB::table('moshina_elons')
                ->where('yoqilgi_turi', 'metan')
                ->update(['yoqilgi_turi' => 'benzin+metan']);

            DB::table('moshina_elons')
                ->where('yoqilgi_turi', 'elektr')
                ->update(['yoqilgi_turi' => 'eloktor']);
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::table('moshina_elons')
                ->where('yoqilgi_turi', 'eloktor')
                ->update(['yoqilgi_turi' => 'elektr']);

            $enumValues = "'" . implode("','", self::OLD_VALUES) . "'";
            DB::statement("ALTER TABLE moshina_elons MODIFY COLUMN yoqilgi_turi ENUM({$enumValues}) NOT NULL");
        }
    }
};
