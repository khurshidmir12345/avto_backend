<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $avtoVodiyPhone = config('chat.avto_vodiy_phone', '+998000000001');

        User::firstOrCreate(
            ['phone' => $avtoVodiyPhone],
            [
                'name' => 'Avto Vodiy',
                'phone' => $avtoVodiyPhone,
                'password' => null,
                'phone_verified_at' => now(),
                'balance' => 0,
                'welcome_bonus_received' => true,
                'is_admin' => false,
            ]
        );

        $adminPhone = '+998990000001'; // Admin uchun maxsus raqam (conflictdan qochish)

        User::updateOrCreate(
            ['email' => 'admin@avtovodiy.uz'],
            [
                'name' => 'Admin',
                'email' => 'admin@avtovodiy.uz',
                'phone' => $adminPhone,
                'password' => 'password',
                'phone_verified_at' => now(),
                'balance' => 0,
                'welcome_bonus_received' => true,
                'is_admin' => true,
            ]
        );
    }
}
