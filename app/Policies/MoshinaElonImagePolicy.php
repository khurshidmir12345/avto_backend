<?php

namespace App\Policies;

use App\Models\MoshinaElonImage;
use App\Models\User;

class MoshinaElonImagePolicy
{
    public function deleteOrphanImage(User $user, MoshinaElonImage $image): bool
    {
        return $user->id === $image->user_id && $image->moshina_elon_id === null;
    }
}
