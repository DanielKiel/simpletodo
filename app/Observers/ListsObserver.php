<?php
/**
 * Created by PhpStorm.
 * User: dk
 * Date: 17.10.17
 * Time: 16:13
 */

namespace App\Observers;


use App\Events\ListsCreated;
use App\Events\ListsUpdated;
use App\Lists;
use App\ListsHistory;
use App\Notifications\ListUpdated;
use App\SharedList;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class ListsObserver
{
    public function creating(Lists $list)
    {
        $user = Auth::user();

        $list->created = $user->id;

        $list->updated = $user->id;

        $list->tenants_id = $user->tenants_id;

        if (empty($list->token)) {
            $list->token = uniqid();
        }

        $list->version = 1;

        //cause when calling Lists::create([]) the configured default will not work on updating and writing history
        if (empty($list->type)) {
            $list->type = 'default';
        }
    }

    public function created(Lists $list)
    {
        $users = SharedList::getFollowers($list->token);

        if ($users->isEmpty()) {
            return;
        }

        broadcast(new ListsCreated($list))->toOthers();

        Notification::send($users, new ListUpdated($list));
    }

    public function updating(Lists $list)
    {
        $list->updated = Auth::id();

        $list->version = $list->version + 1;

        $original = $list->getOriginal();

        //id will become lists_id
        array_set(
            $original,
            'lists_id',
            array_get($original, 'id')
        );

        array_forget($original, 'id');

        ListsHistory::create($original);
    }

    public function updated(Lists $list)
    {
        $users = SharedList::getFollowers($list->token);

        if ($users->isEmpty()) {
            return;
        }

        broadcast(new ListsUpdated($list))->toOthers();

        Notification::send($users, new ListUpdated($list));
    }
}