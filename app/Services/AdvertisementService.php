<?php

namespace App\Services;

use App\Models\Advertisement;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AdvertisementService
{
    public function __construct(
        private readonly BalanceService $balanceService
    ) {}

    public function create(User $user, array $data): Advertisement
    {
        $dailyPrice = Advertisement::getReklamaPrice();
        $days = max(1, min((int) ($data['days'] ?? 1), 30));
        $totalPrice = $dailyPrice * $days;

        return DB::transaction(function () use ($user, $data, $dailyPrice, $days, $totalPrice) {
            $this->balanceService->addDebit(
                $user,
                $totalPrice,
                "Reklama uchun yechildi ({$days} kun)"
            );

            return Advertisement::create([
                'user_id' => $user->id,
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'image_key' => $data['image_key'] ?? null,
                'link' => $data['link'] ?? null,
                'status' => Advertisement::STATUS_PENDING,
                'days' => $days,
                'daily_price' => $dailyPrice,
                'total_price' => $totalPrice,
            ]);
        });
    }

    public function approve(Advertisement $ad, User $admin): Advertisement
    {
        $startedAt = now();
        $expiresAt = $startedAt->copy()->addDays($ad->days);

        $ad->update([
            'status' => Advertisement::STATUS_APPROVED,
            'started_at' => $startedAt,
            'expires_at' => $expiresAt,
            'approved_by' => $admin->id,
            'rejection_reason' => null,
        ]);

        return $ad->fresh();
    }

    public function reject(Advertisement $ad, ?string $reason = null): Advertisement
    {
        return DB::transaction(function () use ($ad, $reason) {
            $ad->update([
                'status' => Advertisement::STATUS_REJECTED,
                'rejection_reason' => $reason,
            ]);

            $this->balanceService->addCredit(
                $ad->user,
                $ad->total_price,
                "Reklama rad etildi — pul qaytarildi"
            );

            return $ad->fresh();
        });
    }

    public function reactivate(Advertisement $ad, User $user): Advertisement
    {
        $dailyPrice = Advertisement::getReklamaPrice();
        $totalPrice = $dailyPrice * $ad->days;

        return DB::transaction(function () use ($ad, $user, $dailyPrice, $totalPrice) {
            $this->balanceService->addDebit(
                $user,
                $totalPrice,
                "Reklama qayta faollashtirildi ({$ad->days} kun)"
            );

            $ad->update([
                'status' => Advertisement::STATUS_PENDING,
                'daily_price' => $dailyPrice,
                'total_price' => $totalPrice,
                'started_at' => null,
                'expires_at' => null,
                'rejection_reason' => null,
                'approved_by' => null,
            ]);

            return $ad->fresh();
        });
    }

    public function expireOldAds(): int
    {
        return Advertisement::where('status', Advertisement::STATUS_APPROVED)
            ->where('expires_at', '<=', now())
            ->update(['status' => Advertisement::STATUS_EXPIRED]);
    }

    public function incrementViews(Advertisement $ad): void
    {
        $ad->increment('views');
    }
}
