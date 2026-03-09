<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$u = App\Models\User::where('email', 'admin@avtovodiy.uz')->first();
if ($u) {
    $u->password = 'password';
    $u->is_admin = true;
    $u->save();
    echo "OK: Admin parol yangilandi. Email: admin@avtovodiy.uz, Parol: password\n";
} else {
    echo "XATO: Admin topilmadi. AdminSeeder ishga tushiring.\n";
    exit(1);
}
