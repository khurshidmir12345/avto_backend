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

        User::firstOrCreate(
            ['email' => 'admin@avtovodiy.uz'],
            [
                'name' => 'Admin',
                'email' => 'admin@avtovodiy.uz',
                'phone' => '+998000000002',
                'password' => 'password',
                'phone_verified_at' => now(),
                'balance' => 0,
                'welcome_bonus_received' => true,
                'is_admin' => true,
            ]
        );
    }
}
