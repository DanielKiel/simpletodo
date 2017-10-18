<?php

namespace App\Policies;

use App\Core\Policies\SuperAdminPolicy;
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
        return true;
    }

    /**
     * Determine whether the user can create lists.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
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
        return true;
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
        return true;
    }
}
