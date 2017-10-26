<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class Dashboard extends Controller
{
    public function tokens()
    {
        $links = json_encode([
            [
                'href' => route('backend.list', ['token' => '_id_']),
                'title' => 'view',
                'icon' => 'subject',
                'class' => '',
                'ajax' => false,
                'replace' => 'token'
            ]
        ]);

        return view('backend.dashboard.lists',[
            'tokensRoute' => route('api.tokens'),
            'links' => $links
        ]);
    }

    public function viewList($token = null)
    {
        return view('backend.dashboard.view_list',[
            'token' => $token,
            'listsRoute' => route('lists.index',['token' => $token])
        ]);
    }
}
