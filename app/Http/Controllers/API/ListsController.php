<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ListsValidation as FormRequest;
use App\Lists;
use Illuminate\Http\Request;

class ListsController extends Controller
{
    public function tokens()
    {
        return Lists::withoutGlobalScopes()->select('token')->groupBy('token')->paginate();
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return Lists::grouped($request->get('token'))->paginate();
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
     * @param  \App\Http\Requests\ListsValidation  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FormRequest $request)
    {
        $this->authorize('create', Lists::class);

        return Lists::create($request->input())->fresh();
    }

    /**
     * Display the specified resource.
     *
     * @param  Lists $list
     * @return \Illuminate\Http\Response
     */
    public function show(Lists $list)
    {
        $this->authorize('view', $list);

        return $list;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\ListsValidation  $request
     * @param  Lists $list
     * @return \Illuminate\Http\Response
     */
    public function update(FormRequest $request, Lists $list)
    {
        $this->authorize('update', $list);

        $list->update($request->input());

        return $list->fresh();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Lists $list
     * @return \Illuminate\Http\Response
     */
    public function destroy(Lists $list)
    {
        $this->authorize('delete', $list);

        $result = $list->delete();

        return  [
            'success' => (bool) $result
        ];
    }
}
