<?php

namespace App\Http\Requests;

use App\Core\Validation\ValidationScenario;

class ListFileValidation extends ValidationScenario
{

    public function onDefault()
    {
        return [
            'lists_id' => 'required'
        ];
    }

    public function onCreate()
    {
        return [
            'lists_id' => 'required',
            'version' => 'required',
            'upload' => 'mimes:jpeg,bmp,png'
        ];
    }

    public function onUpdate()
    {
        return [

        ];
    }
}
