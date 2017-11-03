<?php
/**
 * Created by PhpStorm.
 * User: dk
 * Date: 17.10.17
 * Time: 16:13
 */

namespace App\Observers;


use App\User;
use Illuminate\Support\Facades\Auth;

class UsersObserver
{
    public function creating(User $user)
    {
        $auth = Auth::user();

        if (! $auth instanceof User) {
            return;
        }

        $user->tenants_id = $auth->tenants_id;
    }
}