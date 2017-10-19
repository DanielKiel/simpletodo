<?php
/**
 * Created by PhpStorm.
 * User: dk
 * Date: 17.10.17
 * Time: 16:13
 */

namespace App\Observers;


use App\Comment;
use Illuminate\Support\Facades\Auth;

class CommentsObserver
{
    public function creating(Comment $comment)
    {
        $comment->by = Auth::id();
    }
}