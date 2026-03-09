<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class ResetAdminPassword extends Command
{
    protected $signature = 'admin:reset-password';

    protected $description = 'Admin parolni "password" ga yangilaydi';

    public function handle(): int
    {
        $admin = User::where('email', 'admin@avtovodiy.uz')->first();

        if (!$admin) {
            $this->error('Admin topilmadi. php artisan db:seed --class=AdminSeeder --force ishlating.');
            return self::FAILURE;
        }

        $admin->update([
            'password' => 'password',
            'is_admin' => true,
        ]);

        $this->info('Admin parol va ruxsat yangilandi.');
        $this->info('Email: admin@avtovodiy.uz');
        $this->info('Parol: password');

        return self::SUCCESS;
    }
}
