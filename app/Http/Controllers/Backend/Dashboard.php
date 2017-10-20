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
                'icon' => 'glyphicon glyphicon-eye-open',
                'class' => 'btn btn-simple',
                'ajax' => false,
                'replace' => 'token'
            ]
        ]);

        return view('backend.dashboard.lists',[
            'tokensRoute' => route('api.tokens'),
            'links' => $links
        ]);
    }

    public function viewList($token)
    {
        return view('backend.dashboard.view_list',[
            'token' => $token,
            'listsRoute' => route('lists.index',['token' => $token])
        ]);
    }
}
