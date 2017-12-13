<?php

namespace App\Http\Requests;

use App\Core\Validation\ValidationScenario;

class UserValidation extends ValidationScenario
{

    public function onDefault()
    {
        return [

        ];
    }

    public function onCreate()
    {
        return [
            'name' => 'required',
            'email' => 'required|unique',
            'password' => 'required'
        ];
    }

    public function onUpdate()
    {
        return [

        ];
    }
}
