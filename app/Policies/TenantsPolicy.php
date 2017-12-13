<?php

namespace App\Policies;

use App\Core\Policies\SuperAdminPolicy;
use App\User;
use App\Tenant;
use Illuminate\Auth\Access\HandlesAuthorization;

class TenantsPolicy extends SuperAdminPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the tenant.
     *
     * @param  \App\User  $user
     * @param  \App\Tenant  $tenant
     * @return mixed
     */
    public function view(User $user, Tenant $tenant)
    {
        return false;
    }

    /**
     * Determine whether the user can create tenants.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can update the tenant.
     *
     * @param  \App\User  $user
     * @param  \App\Tenant  $tenant
     * @return mixed
     */
    public function update(User $user, Tenant $tenant)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the tenant.
     *
     * @param  \App\User  $user
     * @param  \App\Tenant  $tenant
     * @return mixed
     */
    public function delete(User $user, Tenant $tenant)
    {
        return false;
    }
}
