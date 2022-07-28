<?php

namespace App\Actions;

use App\Models\User;

class SyncTokenAction
{
    public function updateLastLoggedIn(User $user): void
    {
        $user->update([
            'last_login_at' => now(),
        ]);
    }
}
