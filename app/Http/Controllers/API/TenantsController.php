<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Tenant;
use Illuminate\Http\Request;
use App\Http\Requests\TenantValidation;

class TenantsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return Tenant::grouped()->paginate($request->input('per_page', 15));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  TenantValidation  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TenantValidation $request)
    {
        $this->authorize('create', Tenant::class);

        return Tenant::create($request->input())->fresh();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Tenant  $tenant
     * @return \Illuminate\Http\Response
     */
    public function show(Tenant $tenant)
    {
        $this->authorize('view', $tenant);

        return $tenant;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Tenant  $tenant
     * @return \Illuminate\Http\Response
     */
    public function edit(Tenant $tenant)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  TenantValidation  $request
     * @param  \App\Tenant  $tenant
     * @return \Illuminate\Http\Response
     */
    public function update(TenantValidation $request, Tenant $tenant)
    {
        $this->authorize('update', $tenant);

        $tenant->update($request->input());

        return $tenant->fresh();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Tenant  $tenant
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tenant $tenant)
    {
        $this->authorize('delete', $tenant);

        $result = $tenant->delete();

        return  [
            'success' => (bool) $result
        ];
    }
}
