<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserBalanceHistory;
use Illuminate\Support\Facades\DB;

class BalanceService
{
    public const WELCOME_BONUS_AMOUNT = 50_000;

    /**
     * Foydalanuvchi balansiga pul qo'shadi va history yozadi.
     */
    public function addCredit(User $user, int $amount, string $description): User
    {
        return DB::transaction(function () use ($user, $amount, $description) {
            $user = User::where('id', $user->id)->lockForUpdate()->firstOrFail();
            $newBalance = $user->balance + $amount;

            $user->update(['balance' => $newBalance]);

            UserBalanceHistory::create([
                'user_id' => $user->id,
                'type' => UserBalanceHistory::TYPE_CREDIT,
                'amount' => $amount,
                'balance_after' => $newBalance,
                'description' => $description,
            ]);

            return $user->fresh();
        });
    }

    /**
     * Foydalanuvchi balansidan pul yechadi va history yozadi.
     *
     * @throws \RuntimeException Agar balans yetarli bo'lmasa
     */
    public function addDebit(User $user, int $amount, string $description): User
    {
        return DB::transaction(function () use ($user, $amount, $description) {
            $user = User::where('id', $user->id)->lockForUpdate()->firstOrFail();

            if ($user->balance < $amount) {
                throw new \RuntimeException('Balans yetarli emas');
            }

            $newBalance = $user->balance - $amount;

            $user->update(['balance' => $newBalance]);

            UserBalanceHistory::create([
                'user_id' => $user->id,
                'type' => UserBalanceHistory::TYPE_DEBIT,
                'amount' => $amount,
                'balance_after' => $newBalance,
                'description' => $description,
            ]);

            return $user->fresh();
        });
    }

    /**
     * Birinchi marta kirgan foydalanuvchiga welcome bonus beradi.
     */
    public function giveWelcomeBonus(User $user): User
    {
        $user = $this->addCredit(
            $user,
            self::WELCOME_BONUS_AMOUNT,
            'Sizga bonus sifatida 50 000 UZS taqdim qilindi'
        );

        $user->update(['welcome_bonus_received' => true]);

        return $user->fresh();
    }
}
