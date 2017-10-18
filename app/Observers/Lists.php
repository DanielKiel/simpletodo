<?php
/**
 * Created by PhpStorm.
 * User: dk
 * Date: 17.10.17
 * Time: 16:13
 */

namespace App\Observers;


use App\Lists as Object;
use App\ListsHistory;
use Illuminate\Support\Facades\Auth;

class Lists
{
    public function creating(Object $list)
    {
        $list->created = Auth::id();

        $list->updated = Auth::id();

        if (empty($list->token)) {
            $list->token = uniqid();
        }

        $list->version = 1;

        //cause when calling Lists::create([]) the configured default will not work on updating and writing history
        if (empty($list->type)) {
            $list->type = 'default';
        }
    }

    public function updating(Object $list)
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
}