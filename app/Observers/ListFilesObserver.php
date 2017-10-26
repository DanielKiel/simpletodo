<?php
/**
 * Created by PhpStorm.
 * User: dk
 * Date: 17.10.17
 * Time: 16:13
 */

namespace App\Observers;


use App\ListFile;
use Illuminate\Support\Facades\Auth;

class ListFilesObserver
{
    public function creating(ListFile $listFile)
    {
        $listFile->by = Auth::id();
    }
}