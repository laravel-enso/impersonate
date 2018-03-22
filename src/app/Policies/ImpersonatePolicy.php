<?php

namespace LaravelEnso\Impersonate\app\Policies;

use LaravelEnso\Core\app\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ImpersonatePolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    public function impersonate(User $user, User $targetUser)
    {
        return $user->can('access-route', 'core.impersonate.start')
            && !$targetUser->isAdmin()
            && $user->id !== $targetUser->id
            && !$user->isImpersonating();
    }
}
