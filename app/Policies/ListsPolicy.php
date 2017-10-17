<?php

namespace App\Policies;

use App\User;
use App\Lists;
use Illuminate\Auth\Access\HandlesAuthorization;

class ListsPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the lists.
     *
     * @param  \App\User  $user
     * @param  \App\Lists  $lists
     * @return mixed
     */
    public function view(User $user, Lists $lists)
    {
        //
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
