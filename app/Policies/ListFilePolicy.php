<?php

namespace App\Policies;

use App\User;
use App\ListFile;
use Illuminate\Auth\Access\HandlesAuthorization;

class ListFilePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the listFile.
     *
     * @param  \App\User  $user
     * @param  \App\ListFile  $listFile
     * @return mixed
     */
    public function view(User $user, ListFile $listFile)
    {
        return true;
    }

    /**
     * Determine whether the user can create listFiles.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the listFile.
     *
     * @param  \App\User  $user
     * @param  \App\ListFile  $listFile
     * @return mixed
     */
    public function update(User $user, ListFile $listFile)
    {
        return true;
    }

    /**
     * Determine whether the user can delete the listFile.
     *
     * @param  \App\User  $user
     * @param  \App\ListFile  $listFile
     * @return mixed
     */
    public function delete(User $user, ListFile $listFile)
    {
        return true;
    }
}
