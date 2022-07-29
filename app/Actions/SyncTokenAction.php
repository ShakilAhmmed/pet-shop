<?php

namespace App\Actions;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class SyncTokenAction
{
    public function attach(User $user, $token): void
    {
        $user->update([
            'last_login_at' => now(),
        ]);

        $user->tokens()->where('token', $token)->update([
            'last_used_at' => now()
        ]);
    }

    public function detach(User $user): void
    {
        $user->tokens()->whereNull('expires_at')->update([
            'expires_at' => now()
        ]);
    }
}
