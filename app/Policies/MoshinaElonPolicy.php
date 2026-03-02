<?php

namespace App\Policies;

use App\Models\MoshinaElon;
use App\Models\User;

class MoshinaElonPolicy
{
    public function update(User $user, MoshinaElon $moshinaElon): bool
    {
        return $user->id === $moshinaElon->user_id;
    }

    public function delete(User $user, MoshinaElon $moshinaElon): bool
    {
        return $user->id === $moshinaElon->user_id;
    }

    public function manageImages(User $user, MoshinaElon $moshinaElon): bool
    {
        return $user->id === $moshinaElon->user_id;
    }
}
