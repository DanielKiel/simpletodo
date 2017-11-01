<?php
/**
 * Created by PhpStorm.
 * User: dk
 * Date: 17.10.17
 * Time: 16:13
 */

namespace App\Observers;


use App\Comment;
use App\Notifications\CommentCreated;
use App\Events\CommentCreated as CommentCreatedEvent;
use App\SharedList;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class CommentsObserver
{
    public function creating(Comment $comment)
    {
        $comment->by = Auth::id();
    }

    public function created(Comment $comment)
    {
        $users = SharedList::getFollowers($comment->fresh()->relatedList->token);

        if ($users->isEmpty()) {
            return;
        }

        Notification::send($users, new CommentCreated($comment));

        event(new CommentCreatedEvent($comment));
    }
}