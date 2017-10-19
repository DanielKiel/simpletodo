<?php

namespace App\Policies;

use App\Core\Policies\SuperAdminPolicy;
use App\SharedLists;
use App\User;
use App\Lists;

class ListsPolicy extends SuperAdminPolicy
{

    /**
     * Determine whether the user can view the lists.
     *
     * @param  \App\User  $user
     * @param  \App\Lists  $lists
     * @return mixed
     */
    public function view(User $user, Lists $lists)
    {
        return $this->decideAccess($user, $lists);
    }

    /**
     * Determine whether the user can create lists.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $this->decideAccess($user, $lists);
    }

    /**
     * Determine whether the user can update the lists.
     *
     * @param  \App\User  $user
     * @param  \App\Lists  $lists
     * @return mixed
     */
    public function update(User $user, Lists $lists)
    {
        return $this->decideAccess($user, $lists);
    }

    /**
     * Determine whether the user can delete the lists.
     *
     * @param  \App\User  $user
     * @param  \App\Lists  $lists
     * @return mixed
     */
    public function delete(User $user, Lists $lists)
    {
        return $this->decideAccess($user, $lists);
    }

    private function decideAccess(User $user, Lists $lists)
    {
        if ($lists->created === $user->id) {
            return true;
        }

        if ($lists->updated === $user->id) {
            return true;
        }

        return SharedLists::where('to', $user->id)->where('token', $lists->token)->exists();
    }
}
