<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserValidation;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return User::with('shared')
            ->byTenant()
            //->where('id', '!=', Auth::id())
            ->get();//paginate($request->input('per_page', 15));
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
     * @param  UserValidation  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserValidation $request)
    {
        $this->authorize('create', User::class);

        $input = $request->input();

        array_set($input, 'password', bcrypt($request->input('password')));

        return User::create($request->input())->fresh();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $this->authorize('view', $user);

        return $user;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UserValidation  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UserValidation $request, User $user)
    {
        $this->authorize('update', $user);

        $user->update($request->input());

        return $user->fresh();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        $result = $user->delete();

        return  [
            'success' => (bool) $result
        ];
    }
}
