<?php
/**
 * Created by PhpStorm.
 * User: dk
 * Date: 18.10.17
 * Time: 20:39
 */

namespace App\Core\Policies;


use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SuperAdminPolicy
{
    use HandlesAuthorization;

    public function before(User $user)
    {
        if ($user->isSuperAdmin()) {
            return true;
        }
    }
}