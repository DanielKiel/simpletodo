<?php

namespace App\Http\Controllers\API;

use App\Comment;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\CommentValidation as ValidationRequest;

class CommentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Comment::paginate();
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
     * @param  ValidationRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ValidationRequest $request)
    {
        $this->authorize('create', Comment::class);

        return Comment::create($request->input())->fresh();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show(Comment $comment)
    {
        $this->authorize('view', $comment);

        return $comment;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  ValidationRequest  $request
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(ValidationRequest $request, Comment $comment)
    {
        $this->authorize('update', $comment);

        $comment->update($request->all());

        return $comment->fresh();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);

        $result = $comment->delete();

        return  [
            'success' => (bool) $result
        ];
    }
}
