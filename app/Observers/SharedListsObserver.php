<?php
/**
 * Created by PhpStorm.
 * User: dk
 * Date: 17.10.17
 * Time: 16:13
 */

namespace App\Observers;


use App\SharedList;
use Illuminate\Support\Facades\Auth;

class SharedListsObserver
{
    public function creating(SharedList $sharedList)
    {
        $user = Auth::user();

        $sharedList->tenants_id = $user->tenants_id;
    }
}