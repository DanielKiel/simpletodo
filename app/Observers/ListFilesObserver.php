<?php
/**
 * Created by PhpStorm.
 * User: dk
 * Date: 17.10.17
 * Time: 16:13
 */

namespace App\Observers;


use App\Events\FileUpdated;
use App\Events\FileUploaded;
use App\ListFile;
use App\SharedList;
use Illuminate\Support\Facades\Auth;

class ListFilesObserver
{
    public function creating(ListFile $listFile)
    {
        $listFile->by = Auth::id();
    }

    public function created(ListFile $listFile)
    {
        $users = SharedList::getFollowers($listFile->listObject->token);

        if ($users->isEmpty()) {
            return;
        }

        broadcast(new FileUploaded($listFile))->toOthers();
    }

    public function updated(ListFile $listFile)
    {
        $users = SharedList::getFollowers($listFile->listObject->token);

        if ($users->isEmpty()) {
            return;
        }

        broadcast(new FileUpdated($listFile))->toOthers();
    }
}