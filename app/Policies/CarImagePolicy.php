<?php

namespace App\Policies;

use App\Models\CarImage;
use App\Models\User;

class CarImagePolicy
{
    public function deleteOrphanImage(User $user, CarImage $image): bool
    {
        return $user->id === $image->user_id && $image->car_id === null;
    }
}
